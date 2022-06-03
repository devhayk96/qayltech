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

    /**
     * Display a listing of the resource.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request)
    {
        $devices = Device::query()->where('countryId', $request->get('countryId'));

        if ($organizationId = $request->get('organizationId')) {
            $devices->where('hospital_id', $organizationId);
        }

        if ($hospitalId = $request->get('hospitalId')) {
            $devices->where('hospital_id', $hospitalId);
        }

        return $this->sendResponse($devices->get(), 'List of devices');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        return $this->sendError('Doctor not found');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
