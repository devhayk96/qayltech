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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Organization::query()->where('id', $id)->delete()) {
            return $this->sendResponse([], 'Organization archived successfully');
        }

        return $this->sendError('Organization not found');
    }

    public function delete($id)
    {
        if (Organization::query()->where('id', $id)->forceDelete()) {
            return $this->sendResponse([], 'Organization deleted successfully');
        }

        return $this->sendError('Organization not found');
    }

    public function restore($id)
    {
        if (Organization::query()->where('id', $id)->restore()) {
            return $this->sendResponse([], 'Organization restored successfully');
        }

        return $this->sendError('Organization not found');
    }
}
