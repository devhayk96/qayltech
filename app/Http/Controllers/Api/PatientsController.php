<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MediaHelper;
use App\Http\Requests\Patient\WorkoutInfo\ListRequest as ListWorkoutInfoRequest;
use App\Http\Requests\Patient\WorkoutInfo\StoreRequest as StoreWorkoutInfoRequest;
use App\Http\Resources\PatientCollection;
use App\Http\Resources\PatientResource;
use App\Models\Role;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\User\StoreService;
use App\Models\PatientsAdditionalinfo;
use App\Http\Requests\Patient\ListRequest;
use App\Http\Requests\Patient\StoreRequest;
use App\Http\Requests\Patient\AdditionalInfo\StoreRequest as AdditionalInfoStoreRequest;
use App\Http\Requests\Patient\AssignPatientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        $patients = Patient::query()
            ->where('country_id', $request->get('countryId'));

        if ($doctorId = $request->get('doctorId') || (current_user_role() == Role::ALL['doctor'])) {
            $patients->whereHas('doctors', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            });
        }
        if ($organizationId = $request->get('organizationId')) {
            $patients->where('organization_id', $organizationId);
        }
        if ($hospitalId = $request->get('hospitalId')) {
            $patients->where('hospital_id', $hospitalId);
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

            if ($patient->save() && !$isIndividual) {
                $patient->doctors()->sync([
                    $doctorId => [
                        'device_id' => $deviceId,
                        'workout_start' => $request->get('workout_start'),
                        'workout_end' => $request->get('workout_end'),
                        'created_at' => now()
                    ]
                ]);

                DB::commit();
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
    public function additionalInfos(Patient $patient)
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
                PatientsAdditionalinfo::create([
                    'patient_id' => $request->get('patientId'),
                    'key' => $additionalInfo['key'],
                    'value' => $additionalInfo['value']
                ]);
            }
        }

        return $this->sendResponse($doctor, 'Patient successfully assigned');
    }

    public function workoutInfos(ListWorkoutInfoRequest $request, Patient $patient)
    {
        return $this->sendResponse($patient->workoutInfos, 'Patient workout infos list');
    }

    public function storeWorkoutInfo(StoreWorkoutInfoRequest $request, Patient $patient)
    {
        $newInfo = $patient->workoutInfos()->create([
            'key' => $request->get('key'),
            'status' => $request->get('status'),
        ]);

        $additionalInfos = $request->get('additionalInfos');

        if ($additionalInfos && !empty($additionalInfos)) {
            foreach ($additionalInfos as $additionalInfo) {
                $newInfo->additionalInfos()->create([
                    'patient_id' => $patient->id,
                    'key' => $additionalInfo['key'],
                    'value' => $additionalInfo['value']
                ]);
            }
        }

        return $this->sendResponse($newInfo, 'Workout information successfully saved');
    }


}
