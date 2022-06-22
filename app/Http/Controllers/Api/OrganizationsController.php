<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Organization\ListRequest;
use App\Http\Requests\Organization\StoreRequest;
use App\Models\Organization;
use App\Models\Role;
use App\Services\User\StoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrganizationsController extends BaseController
{
    protected function resourceName() : string
    {
        return 'organizations';
    }

    protected function roleName() : string
    {
        return 'organization';
    }

    /**
     * Return a listing of the organizations.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $organizations = Organization::query()
            ->where('country_id', $request->get('countryId'));

        if ($organizationName = $request->get('name')) {
            $organizations->where('name', 'LIKE', $organizationName .'%');
        }

        return $this->sendResponse($organizations->get(), 'Organizations List');
    }

    /**
     * Store a newly created organization in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $request->merge(['role_id' => Role::ALL['organization']]);
            $organizationUser = (new StoreService($request))->run();

            $organization = Organization::create([
                'name' => $request->get('name'),
                'address' => $request->get('address'),
                'category_id' => $request->get('categoryId'),
                'country_id' => $request->get('countryId'),
                'user_id' => $organizationUser['id']
            ]);

            DB::commit();
            return $this->sendResponse($organizationUser, 'Organization user successfully created');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Something went wrong', 500, [$exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        if ($organization = Organization::find($id)) {
            return $this->sendResponse($organization, $organization->name);
        }

        return $this->sendError('Organization not found');
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
     * Archive the specified organization in database.
     *
     * @param $userId
     * @return JsonResponse
     */
    public function destroy($userId)
    {
        return $this->removeResource($userId, 'delete', 'archive');
    }

    /**
     * Permanently delete the specified organization from database
     *
     * @param $userId
     * @return JsonResponse
     */
    public function delete($userId)
    {
        return $this->removeResource($userId, 'forceDelete', 'permanently delete');
    }

    /**
     * Restore temporary deleted(archived) organization
     * @param $userId
     * @return JsonResponse
     */
    public function restore($userId)
    {
        return $this->removeResource($userId, 'restore', 'restore');
    }
}
