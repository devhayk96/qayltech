<?php

namespace App\Http\Controllers\Api;

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
        $this->middleware(["permission:{$this->permissionKeyName} assign,api"])->only(['assignPatient']);
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


            if ($image = $request->file('image')) {
                $imageExt = $image->getClientOriginalExtension();
                $imageName = Str::random(8) . "{$patientUser['id']}." . $imageExt;
                $imagePath = $image->store('public/images/'. $imageName);
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
                'image' => $imagePath,
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Patient::query()->where('id', $id)->delete()) {
            return $this->sendResponse([], 'Patient deleted successfully');
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
    public function createAdditionalInfo(AdditionalInfoStoreRequest $request, Patient $patient)
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

    public function delete($id)
    {

    }

    public function restore($id)
    {

    }
}
