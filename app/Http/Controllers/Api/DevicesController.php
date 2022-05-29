<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Device\StoreRequest;
use App\Http\Requests\Device\ListRequest;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevicesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('hasAccess')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request)
    {
        $devices = Device::query();

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
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $device = Device::create([
                'code' => $request->get('code'),
                'hospital_id' => $request->get('hospital_id'),
            ]);

            DB::commit();
            return $this->sendResponse($device, 'Device successfully created');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Something went wrong', 500, [$exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
