<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Device\StoreRequest;
use App\Http\Requests\Device\ListRequest;
use App\Models\Country;
use App\Models\Device;
use App\Models\Doctor;
use App\Models\Organization;
use App\Models\Role;
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
    public function index(ListRequest $request): JsonResponse
    {
        $userId = current_user()->id;
        $devices = Device::query();
        $columnNames = [];
        $relationColumnNames = [];

        if (current_user_role() == Role::ALL['organization']){
            $orgId = Organization::query()->where('user_id', $userId)->pluck('id');
            $devices->where('organization_id', $orgId);
            $columnNames = ['hospital_id'];
        } elseif (current_user_role() == Role::ALL['country']){
            $devices->where('country_id', current_user(['country'])->country->id);
            $columnNames = ['organization_id', 'hospital_id'];
        } elseif (is_doctor()) {
            $devices->where('hospital_id', current_user(['doctor'])->doctor->hospital_id);
        } elseif (is_super_admin()) {
            $columnNames = ['country_id', 'organization_id', 'hospital_id'];
            $relationColumnNames = ['doctor_id', 'patient_id'];
        } else {
            return $this->sendResponse([], 'Empty result');
        }

        if (!empty($columnNames)) {
            foreach ($columnNames as $columnName) {
                $paramName = str_replace('_id', 'Id', $columnName);
                if ($param = $request->get($paramName)) {
                    $devices->where($columnName, $param);
                }
            }
        }

        if (!empty($relationColumnNames)) {
            foreach ($relationColumnNames as $relationColumnName) {
                $paramName = str_replace('_id', 'Id', $relationColumnName);
                if ($param = $request->get($paramName)) {
                    $relationName = str_replace('_id', 's', $relationColumnName);
                    $devices->whereHas($relationName, function ($q) use ($relationColumnName, $param) {
                        $q->where($relationColumnName, $param);
                    });
                }
            }
        }

        return $this->sendResponse($devices->get(), 'List of devices');
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
                'country_id' => $request->get('countryId'),
                'hospital_id' => $request->get('hospitalId'),
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
    public function show($id): JsonResponse
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
