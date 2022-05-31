<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Hospital\StoreRequest;
use App\Models\Hospital;
use App\Models\Role;
use App\Services\User\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class HospitalsController extends BaseController
{
    /**
     * Return a listing of the hospitals.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $country_id = $request->country_id;
        $query = Hospital::all()->where('country_id', '=', $country_id);
        if ($request->organization_id){
            $organization_id = $request->organization_id;
            $query = $query->where('organization_id', '=', $organization_id);
        }
        return $query;
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
                'user_id' => $hospitalUser->id
            ]);

            DB::commit();
            return $this->sendResponse($hospitalUser, 'Hospital user successfully created');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Something went wrong', 500, [$exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
