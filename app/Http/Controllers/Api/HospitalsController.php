<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Hospital\StoreRequest;
use App\Http\Requests\Hospital\ListRequest;
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
        $user = current_user()->id;
        if (current_user_role_name() == 'organization'){
            $orgId = Organization::query()->where('user_id', $user)->pluck('id');
            $hospitals = Hospital::query()->where('organization_id', $orgId);

            if ($hospitalName = $request->get('name')) {
                $hospitals->where('name', 'LIKE', $hospitalName .'%');
            }

            return $this->sendResponse($hospitals->get(), 'Hospitals List');
        }
        if (current_user_role_name() == 'country'){
            $cntId = Country::query()->where('user_id', $user)->pluck('id');
            $hospitals = Hospital::query()->where('country_id', $cntId);

            if ($hospitalName = $request->get('name')) {
                $hospitals->where('name', 'LIKE', $hospitalName .'%');
            }

            return $this->sendResponse($hospitals->get(), 'Hospitals List');
        }
//        $country_id = $request->get('countryId');
//        $hospitals = Hospital::query()
//            ->where('country_id', $country_id);
//
//        if ($organizationId = $request->get('organizationId')){
//            $hospitals->where('organization_id', $organizationId);
//        }
//        if ($hospitalName = $request->get('name')) {
//            $hospitals->where('name', 'LIKE', $hospitalName .'%');
//        }
//        return $this->sendResponse($hospitals->get(), 'Hospitals List');
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
