<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Device\StoreRequest;
use App\Http\Requests\Device\ListRequest;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevicesController extends BaseController
{
    protected function resourceName() : string
    {
        return 'devices';
    }

    protected function modelOrRoleName() : string
    {
        return 'device';
    }

    /**
     * Display a listing of the resource.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request)
    {

        $user = current_user()->id;
        if (current_user_role_name() == 'organization'){
            $orgId = Organization::query()->where('user_id', $user)->pluck('id');
            $devices = Device::query()->where('organization_id', $orgId);

            if ($organizationId = $request->get('organizationId')) {
                $devices->where('hospital_id', $organizationId);
            }

            if ($hospitalId = $request->get('hospitalId')) {
                $devices->where('hospital_id', $hospitalId);
            }

            return $this->sendResponse($devices->get(), 'List of devices');
        }
        elseif (current_user_role_name() == 'country'){
            $cntId = Country::query()->where('user_id', $user)->pluck('id');
            $devices = Device::query()->where('country_id', $cntId);

            if ($organizationId = $request->get('organizationId')) {
                $devices->where('hospital_id', $organizationId);
            }

            if ($hospitalId = $request->get('hospitalId')) {
                $devices->where('hospital_id', $hospitalId);
            }

            return $this->sendResponse($devices->get(), 'List of devices');
        }

    }

    /**
     * Store a newly created device in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $device = Device::create([
                'code' => $request->get('code'),
                'hospital_id' => $request->get('hospitalId'),
                'country_id' => $request->get('countryId'),
                'organization_id' => $request->get('organizationId'),
            ]);

            DB::commit();
            return $this->sendResponse($device, 'Device successfully created');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Something went wrong', 500, [$exception->getMessage()]);
        }
    }

    /**
     * Return the specified device.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        if ($device = Device::with('hospital')->find($id)) {
            return $this->sendResponse($device, $device->code);
        }

        return $this->sendError('Device not found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
