<?php

namespace App\Http\Controllers\Api;

use App\Enums\WorkoutStatuses;
use App\Helpers\MediaHelper;
use App\Http\Requests\Patient\WorkoutInfo\ListRequest as ListWorkoutInfoRequest;
use App\Http\Requests\Patient\WorkoutInfo\StoreOculusRequest;
use App\Http\Requests\Patient\WorkoutInfo\StoreRequest as StoreWorkoutInfoRequest;
use App\Http\Resources\PatientCollection;
use App\Http\Resources\PatientResource;
use App\Models\Device;
use App\Models\PatientWorkoutInfo;
use App\Models\Role;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\User\StoreService;
use App\Models\PatientAdditionalInfo;
use App\Http\Requests\Patient\ListRequest;
use App\Http\Requests\Patient\StoreRequest;
use App\Http\Requests\Patient\AdditionalInfo\StoreRequest as AdditionalInfoStoreRequest;
use App\Http\Requests\Patient\AssignPatientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PatientsController extends BaseController
{
    use MediaHelper;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(["permission:{$this->permissionKeyName} assign,api"])->only('assignPatient');
    }

    protected function resourceName() : string
    {
        return 'patients';
    }

    protected function modelOrRoleName() : string
    {
        return 'patient';
    }

    /**
     * Display a listing of the patients.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $patients = Patient::query();

        if (is_super_admin()) {
            if ($doctorId = $request->get('doctorId')) {
                $patients->whereHas('doctors', function ($query) use ($doctorId) {
                    $query->where('doctor_id', $doctorId);
                });
            }

            if ($countryId = $request->get('countryId')) {
                $patients->where('country_id', $countryId);
            }
            if ($organizationId = $request->get('organizationId')) {
                $patients->where('organization_id', $organizationId);
            }
            if ($hospitalId = $request->get('hospitalId')) {
                $patients->where('hospital_id', $hospitalId);
            }
            if ($patientName = $request->get('name')) {
                $patients->where('name', 'LIKE', $patientName .'%');
            }
        } elseif (is_doctor()) {
            $currentUser = current_user([Role::ALL['doctor']]);
            $doctorId = Doctor::query()->where('user_id', $currentUser->id)->value('id');
            $patients->whereHas('doctors', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            });
        } elseif (is_patient()) {
            return $this->sendError(["You don't have permission to see patients list"], 403);
        } else {
            $otherRoles = [
                'country',
                'organization',
                'hospital',
            ];
            foreach ($otherRoles as $otherRole) {
                if (current_user_role() == Role::ALL[$otherRole]) {
                    $currentUser = current_user([$otherRole]);
                    $patients->where("{$otherRole}_id", $currentUser->{$otherRole}->id);
                }
            }
        }

        if ($isIndividual = $request->get('isIndividual')) {
            $patients->where('is_individual', $isIndividual);
        }

        return $this->sendResponse(new PatientCollection($patients->get()), 'Patients List');
    }

    /**
     * Store a newly created patient in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $firstName = $request->get('firstName');
            $lastName = $request->get('lastName');

            $request->merge(['role_id' => Role::ALL['patient'], 'name' => "$firstName $lastName"]);
            $patientUser = (new StoreService($request))->run();

            $isIndividual = true;
            $imagePath = $pdfPath = null;

            $deviceId = $request->get('deviceId');
            $doctorId = $request->get('doctorId');

            if ($doctorId || current_user_role() == Role::ALL['doctor']) {
                $isIndividual = false;
            }

            if ($pdf = $request->file('pdf')) {
                $pdfData = $this->upload($pdf,'pdf/patients/', $patientUser['id']);
                $pdfPath = $pdfData['filePath'];
            }

            if ($image = $request->file('image')) {
                $imageData = $this->upload($image,'images/patients/', $patientUser['id']);
                $imagePath = $imageData['filePath'];
            }

            /*if ($image = $request->get('image')) {
                $base64Image = explode(";base64,", $image);
                $explodeImage = explode("image/", $base64Image[0]);
                $imageType = $explodeImage[1];
                $image_base64 = base64_decode($base64Image[1]);
                $uniqueId = uniqid();
                $imagePath = "/images/patients/{$uniqueId}.{$imageType}";
                Storage::disk('public')->put($imagePath, $image_base64);
            }*/



            $patient = (new Patient())->fill([
                'user_id' => $patientUser['id'],
                'country_id' => $request->get('countryId'),
                'organization_id' => $request->get('organizationId'),
                'hospital_id' => $request->get('hospitalId'),
                'first_name' => $request->get('firstName'),
                'last_name' => $request->get('lastName'),
                'birth_date' => $request->get('birthDate'),
                'disability_date' => $request->get('disabilityDate'),
                'disability_reason' => $request->get('disabilityReason'),
                'disability_category' => $request->get('disabilityCategory'),
                'workout_begin' => $request->get('workoutBegin'),
                'injury' => $request->get('injury'),
                'is_individual' => $isIndividual,
                'image' => $imagePath,
                'pdf' => $pdfPath,
            ]);

            if ($patient->save()) {
                if (!$isIndividual) {
                    $patient->doctors()->sync([
                        $doctorId => [
                            'device_id' => $deviceId,
                            'workout_start' => $request->get('workout_start'),
                            'workout_end' => $request->get('workout_end'),
                            'created_at' => now()
                        ]
                    ]);
                }

                DB::commit();

                $patientUser['user_id'] = $patientUser['id'];
                $patientUser['patient_id'] = $patient->id;
                return $this->sendResponse($patientUser, 'Patient successfully created');
            }

            DB::rollBack();
            return $this->sendError("Patient doesn't created", 400);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->sendError('Something went wrong', 500, [$exception->getMessage()]);
        }
    }

    /**
     * Return the specified patient.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        if ($patient = Patient::with('doctors')->find($id)) {
            return $this->sendResponse(new PatientResource($patient), "$patient->first_name $patient->last_name");
        }

        return $this->sendError('Patient not found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Display a listing of the patient additional infos.
     *
     * @param Patient $patient
     * @return JsonResponse
     */
    public function getAdditionalInfos(Patient $patient)
    {
        return $this->sendResponse($patient->additionalInfos, 'Patient additional infos list');
    }

    /**
     * @param AdditionalInfoStoreRequest $request
     * @param Patient $patient
     * @return JsonResponse
     */
    public function storeAdditionalInfo(AdditionalInfoStoreRequest $request, Patient $patient)
    {
        $newInfo = $patient->additionalInfos()->create([
            'key' => $request->get('key'),
            'value' => $request->get('value'),
            'workout_id' => $request->get('workoutId'),
        ]);

        return $this->sendResponse($newInfo, 'Additional information successfully created');
    }

    /**
     * Assign patient to doctor
     *
     * @param AssignPatientRequest $request
     * @return JsonResponse
     */
    public function assignPatient(AssignPatientRequest $request)
    {
        $doctor = Doctor::find($request->get('doctorId'));
        $doctor->patients()->attach([
            'patient_id' => $request->get('patientId'),
            'device_id'  => $request->get('deviceId'),
        ]);

        $additionalInfos = $request->get('additionalInfos');

        if ($additionalInfos && !empty($additionalInfos)) {
            foreach ($additionalInfos as $additionalInfo){
                PatientAdditionalInfo::create([
                    'patient_id' => $request->get('patientId'),
                    'key' => $additionalInfo['key'],
                    'value' => $additionalInfo['value']
                ]);
            }
        }

        return $this->sendResponse($doctor, 'Patient successfully assigned');
    }

    public function getWorkoutInfo(ListWorkoutInfoRequest $request, Patient $patient): JsonResponse
    {
        $workoutInfos = PatientWorkoutInfo::query()
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('SUM(walk_count) as walk_count')
            ->selectRaw('SUM(steps_count) as steps_count')
            ->selectRaw('MAX(steps_opening) as steps_opening')
            ->selectRaw('MAX(speed) as speed')
            ->selectRaw('SUM(passed_way) as passed_way')
            ->selectRaw('SUM(calories) as calories')
            ->selectRaw('SUM(spent_time) as spent_time')
            ->where('patient_id', $patient->id)
            ->where('created_at', '>=', Carbon::now()->subMonth());

        if ($game = $request->get('game')) {
            $workoutInfos = $workoutInfos->where('game', $game);
        }

        if ($deviceId = $request->get('deviceId')) {
            $workoutInfos = $workoutInfos->where('device_id', $deviceId);
        }

        $workoutInfos = $workoutInfos
            ->groupBy('date')
            ->orderBy('date', 'DESC');

        return $this->sendResponse($workoutInfos->get(), 'Patient workout infos list');
    }

    public function startWorkout(StoreWorkoutInfoRequest $request, Patient $patient): JsonResponse
    {
        $deviceCode = $request->get('deviceCode');
        $device = Device::query()->where(['code' => $deviceCode])->first();

        $workoutInfo = $patient->workoutInfos()
            ->where([
                'device_id' => $device->id,
                'status' => WorkoutStatuses::START,
            ])
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($workoutInfo) {
            return $this->sendResponse([], 'Workout already started or device already in use');
        }

        $newInfo = $patient->workoutInfos()
            ->create([
                'device_id' => $device->id,
                'status' => WorkoutStatuses::START,
            ]);

        return $this->sendResponse($newInfo, 'Workout started');
    }

    public function storeOculusWorkoutInfo(StoreOculusRequest $request): JsonResponse
    {
        $deviceId = Device::query()
            ->where('code', $request->get('deviceCode'))
            ->pluck('id')
            ->first();

        $workoutInfo = PatientWorkoutInfo::query()
            ->select('patient_id', 'id')
            ->whereDate('created_at', Carbon::today())
            ->where('device_id', $deviceId)
            ->whereIn('status', [WorkoutStatuses::START, WorkoutStatuses::IN_PROGRESS])
            ->first();

        if ($workoutInfo) {
            try {
                $workoutInfo->update([
                    'status' => $request->get('status'),
                    'game' => $request->get('game'),
                    'walk_count' => $request->get('walk_count'),
                    'steps_count' => $request->get('steps_count'),
                    'steps_opening' => $request->get('steps_opening'),
                    'speed' => $request->get('speed'),
                    'passed_way' => $request->get('passed_way'),
                    'calories' => $request->get('calories'),
                    'spent_time' => $request->get('spent_time'),
                    'key1' => $request->get('key1'),
                    'key2' => $request->get('key2'),
                    'key3' => $request->get('key3'),
                ]);
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage(), 500);
            }

            return $this->sendResponse($workoutInfo, 'Workout information successfully saved');
        }

        return $this->sendError("Patient did'nt find or didn't start the workout", 400);
    }




}
