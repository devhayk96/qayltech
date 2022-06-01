<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Patient\StoreRequest;
use App\Http\Requests\Patient\ListRequest;
use App\Models\Patient;
use App\Services\User\StoreService;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PatientsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $patients = Patient::query()->where('doctor_id', $request->get('doctor_id'));
        if ($isIndividual = $request->get('isIndividual')){
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
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $firstName = $request->get('firstName');
            $lastName = $request->get('lastName');

            $request->merge(['role_id' => Role::ALL['patient'], 'name' => "$firstName $lastName"]);
            $patientUser = (new StoreService($request))->run();

            $patient = Patient::create([
                'first_name' => $request->get('firstName'),
                'last_name' => $request->get('lastName'),
                'birth_date' => $request->get('birthDate'),
                'disability_date' => $request->get('disabilityDate'),
                'disability_reason' => $request->get('disabilityReason'),
                'disability_category' => $request->get('disabilityCategory'),
                'workout_begin' => $request->get('workoutBegin'),
                'injury' => $request->get('injury'),
                'is_individual' => $request->get('isIndividual'),
                'image' => $request->get('image'),
                'pdf' => $request->get('pdf'),
                'user_id' => $patientUser->id
            ]);

            DB::commit();
            return $this->sendResponse($patientUser, 'Hospital user successfully created');
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
        if ($patient = Patient::find($id)) {
            return $this->sendResponse($patient, "$patient->first_name . $patient->last_name");
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
        //
    }
}
