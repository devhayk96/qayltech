<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Hospital\StoreRequest;
use App\Http\Requests\Hospital\ListRequest;
use App\Http\Resources\HospitalCollection;
use App\Models\Country;
use App\Models\Hospital;
use App\Models\Organization;
use App\Models\Role;
use App\Services\User\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class HospitalsController extends BaseController
{
    protected function resourceName() : string
    {
        return 'hospitals';
    }

    protected function modelOrRoleName() : string
    {
        return 'hospital';
    }

    /**
     * Return a listing of the hospitals.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {

        $hospitals = Hospital::query();

        if (is_super_admin()) {

            if ($countryId = $request->get('countryId')) {
                $hospitals->where('country_id', $countryId);
            }
            if ($organizationId = $request->get('organizationId')) {
                $hospitals->where('organization_id', $organizationId);
            }

        } else {
            if ($countryId = $request->get('countryId')) {
                $hospitals->where('country_id', $countryId);
            }
            if ($organizationId = $request->get('organizationId')) {
                $hospitals->where('organization_id', $organizationId);
            }
            $otherRoles = [
                'country',
                'organization',
            ];
            foreach ($otherRoles as $otherRole) {
                if (current_user_role() == Role::ALL[$otherRole]) {
                    $currentUser = current_user([$otherRole]);
                    $hospitals->where("{$otherRole}_id", $currentUser->{$otherRole}->id);
                }
            }
        }


        return $this->sendResponse(new HospitalCollection($hospitals->get()), 'Hospitals List');
    }

    /**
     * Store a newly created hospital in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $request->merge(['role_id' => Role::ALL['hospital']]);
            $hospitalUser = (new StoreService($request))->run();

            $hospital = Hospital::create([
                'name' => $request->get('name'),
                'address' => $request->get('address'),
                'category_id' => $request->get('categoryId'),
                'organization_id' => $request->get('organizationId'),
                'country_id' => $request->get('countryId'),
                'user_id' => $hospitalUser['id']
            ]);

            DB::commit();
            return $this->sendResponse($hospitalUser, 'Hospital user successfully created');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Something went wrong', 500, [$exception->getMessage()]);
        }
    }

    /**
     * Return the specified hospital.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        if ($hospital = Hospital::with('organization')->find($id)) {
            return $this->sendResponse($hospital, $hospital->name);
        }

        return $this->sendError('Hospital not found');
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
