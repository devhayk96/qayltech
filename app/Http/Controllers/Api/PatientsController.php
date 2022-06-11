<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Patient\WorkoutInfo\ListRequest as ListWorkoutInfoRequest;
use App\Http\Requests\Patient\WorkoutInfo\StoreRequest as StoreWorkoutInfoRequest;
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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PatientsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(["permission:{$this->permissionKeyName} assign,api"])->only('assignPatient');
    }

    protected function resourceName() : string
    {
        return 'patients';
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

        return $this->sendResponse($patients->get(), 'Patients List');

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

            $doctorId = $request->get('doctorId');
            if ($doctorId || current_user_role() == Role::ALL['doctor']) {
                $isIndividual = false;
            }

            if ($pdf = $request->file('pdf')) {
                $pdfExt = $pdf->getClientOriginalExtension();
                $pdfName = Str::random(8) . "{$patientUser['id']}." . $pdfExt;
                $pdfPath = $pdf->store('public/pdf/'. $pdfName);
            }

//
//            if ($image = $request->file('image')) {
//                $imageExt = $image->getClientOriginalExtension();
//                $imageName = Str::random(8) . "{$patientUser['id']}." . $imageExt;
//                $imagePath = $image->store('public/images/'. $imageName);
//            }


            $fileName = '';

            dd($request->all());

            if ($image = $request->file('image')) {

//                $image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAApgAAAKYB3X3/OAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAANCSURBVEiJtZZPbBtFFMZ/M7ubXdtdb1xSFyeilBapySVU8h8OoFaooFSqiihIVIpQBKci6KEg9Q6H9kovIHoCIVQJJCKE1ENFjnAgcaSGC6rEnxBwA04Tx43t2FnvDAfjkNibxgHxnWb2e/u992bee7tCa00YFsffekFY+nUzFtjW0LrvjRXrCDIAaPLlW0nHL0SsZtVoaF98mLrx3pdhOqLtYPHChahZcYYO7KvPFxvRl5XPp1sN3adWiD1ZAqD6XYK1b/dvE5IWryTt2udLFedwc1+9kLp+vbbpoDh+6TklxBeAi9TL0taeWpdmZzQDry0AcO+jQ12RyohqqoYoo8RDwJrU+qXkjWtfi8Xxt58BdQuwQs9qC/afLwCw8tnQbqYAPsgxE1S6F3EAIXux2oQFKm0ihMsOF71dHYx+f3NND68ghCu1YIoePPQN1pGRABkJ6Bus96CutRZMydTl+TvuiRW1m3n0eDl0vRPcEysqdXn+jsQPsrHMquGeXEaY4Yk4wxWcY5V/9scqOMOVUFthatyTy8QyqwZ+kDURKoMWxNKr2EeqVKcTNOajqKoBgOE28U4tdQl5p5bwCw7BWquaZSzAPlwjlithJtp3pTImSqQRrb2Z8PHGigD4RZuNX6JYj6wj7O4TFLbCO/Mn/m8R+h6rYSUb3ekokRY6f/YukArN979jcW+V/S8g0eT/N3VN3kTqWbQ428m9/8k0P/1aIhF36PccEl6EhOcAUCrXKZXXWS3XKd2vc/TRBG9O5ELC17MmWubD2nKhUKZa26Ba2+D3P+4/MNCFwg59oWVeYhkzgN/JDR8deKBoD7Y+ljEjGZ0sosXVTvbc6RHirr2reNy1OXd6pJsQ+gqjk8VWFYmHrwBzW/n+uMPFiRwHB2I7ih8ciHFxIkd/3Omk5tCDV1t+2nNu5sxxpDFNx+huNhVT3/zMDz8usXC3ddaHBj1GHj/As08fwTS7Kt1HBTmyN29vdwAw+/wbwLVOJ3uAD1wi/dUH7Qei66PfyuRj4Ik9is+hglfbkbfR3cnZm7chlUWLdwmprtCohX4HUtlOcQjLYCu+fzGJH2QRKvP3UNz8bWk1qMxjGTOMThZ3kvgLI5AzFfo379UAAAAASUVORK5CYII=";

                $folderPath = storage_path("app\public\images\'");
                $folderPath = explode("'", $folderPath)[0];
                $base64Image = explode(";base64,", $image);
                $explodeImage = explode("image/", $base64Image[0]);
                $imageType = $explodeImage[1];
                $image_base64 = base64_decode($base64Image[1]);
                $file = $folderPath . uniqid().'.'.$imageType;
                $fileName = explode('app', $folderPath)[1].uniqid().'.'.$imageType;
                file_put_contents($file, $image_base64);
            }



            $patient = Patient::create([
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
                'image' => $fileName,
                'pdf' => $pdfPath,
            ]);

            if ($doctorId && ($deviceId = $request->get('deviceId'))) {
                $patient->doctors()->sync([
                    'doctor_id' => $doctorId,
                    'device_id' => $deviceId
                ]);
            }

            DB::commit();
            return $this->sendResponse($patientUser, 'Patient successfully created');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Something went wrong', 500, [$exception->getMessage(), $exception->getLine()]);
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
            return $this->sendResponse($patient, "$patient->first_name $patient->last_name");
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
     * Archive the specified patient in database.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (Patient::query()->where('id', $id)->delete()) {
            return $this->sendResponse([], 'Patient archived successfully');
        }

        return $this->sendError('Patient not found');
    }

    public function delete($id)
    {
        if (Patient::query()->where('id', $id)->forceDelete()) {
            return $this->sendResponse([], 'Patient deleted successfully');
        }

        return $this->sendError('Patient not found');
    }

    public function restore($id)
    {
        if (Patient::query()->where('id', $id)->restore()) {
            return $this->sendResponse([], 'Patient restored successfully');
        }

        return $this->sendError('Patient not found');
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

        return $this->sendResponse($newInfo, 'Workout information successfully saved');
    }


}
