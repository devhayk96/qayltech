<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Doctor\ListRequest;
use App\Http\Requests\Doctor\StoreRequest;
use App\Models\Doctor;
use App\Models\Role;
use App\Http\Resources\DoctorCollection;
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

    protected function modelOrRoleName() : string
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
        $doctors = Doctor::query();

        if (is_super_admin()) {
            if ($countryId = $request->get('countryId')) {
                $doctors->where('country_id', $countryId);
            }
            if ($organizationId = $request->get('organizationId')) {
                $doctors->where('organization_id', $organizationId);
            }
            if ($hospitalId = $request->get('hospitalId')) {
                $doctors->where('hospital_id', $hospitalId);
            }
            if ($doctorName = $request->get('name')) {
                $doctors->where('name', 'LIKE', $doctorName .'%');
            }
        } else {
            $otherRoles = [
                'country',
                'organization',
                'hospital',
            ];
            foreach ($otherRoles as $otherRole) {
                if (current_user_role() == Role::ALL[$otherRole]) {
                    $currentUser = current_user([$otherRole]);
                    $doctors->where("{$otherRole}_id", $currentUser->{$otherRole}->id);
                }
            }
        }

        return $this->sendResponse(new DoctorCollection($doctors->get()), 'Doctors List');
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

}
