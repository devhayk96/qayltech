<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Doctor\ListRequest;
use App\Http\Requests\Doctor\StoreRequest;
use App\Models\Doctor;
use App\Models\Role;
use App\Services\User\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DoctorsController extends BaseController
{
    protected function resourceName() : string
    {
        return 'doctors';
    }

    protected function roleName() : string
    {
        return 'doctor';
    }

    /**
     * Display a listing of the resource.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request)
    {
        $doctors = Doctor::query()
            ->where('country_id', $request->get('countryId'));

        if ($doctorName = $request->get('name')) {
            $doctors->where('first_name', 'LIKE', $doctorName .'%');
        }

        if ($organizationId = $request->get('organizationId')) {
            $doctors->where('organization_id', $organizationId);
        }
        if ($hospitalId = $request->get('hospitalId')) {
            $doctors->where('hospital_id', $hospitalId);
        }

        return $this->sendResponse($doctors->get(), 'List of doctors');
    }

    /**
     * Store a newly created doctor in database.
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

            $request->merge(['role_id' => Role::ALL['doctor'], 'name' => "$firstName $lastName"]);
            $doctorUser = (new StoreService($request))->run();

            $doctor = Doctor::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'country_id' => $request->get('countryId'),
                'profession' => $request->get('profession'),
                'hospital_id' => $request->get('hospitalId'),
                'organization_id' => $request->get('organizationId'),
                'user_id' => $doctorUser['id']
            ]);


            DB::commit();
            return $this->sendResponse($doctorUser, 'Doctor successfully created');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Something went wrong', 500, [$exception->getMessage()]);
        }
    }

    /**
     * Return the specified doctor.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        if ($doctor = Doctor::with(['patients', 'hospital'])->find($id)) {
            return $this->sendResponse($doctor, "$doctor->first_name $doctor->last_name");
        }

        return $this->sendError('Doctor not found');
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
     * Archive the specified doctor in database.
     *
     * @param $userId
     * @return JsonResponse
     */
    public function destroy($userId)
    {
        return $this->removeResource($userId, 'delete', 'archive');
    }

    /**
     * Permanently delete the specified doctor from database
     *
     * @param $userId
     * @return JsonResponse
     */
    public function delete($userId)
    {
        return $this->removeResource($userId, 'forceDelete', 'permanently delete');
    }

    /**
     * Restore temporary deleted(archived) doctor
     * @param $userId
     * @return JsonResponse
     */
    public function restore($userId)
    {
        return $this->removeResource($userId, 'restore', 'restore');
    }
}
