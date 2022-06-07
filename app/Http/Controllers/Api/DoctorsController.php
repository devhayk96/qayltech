<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Doctor\ListRequest;
use App\Http\Requests\Doctor\StoreRequest;
use App\Models\Doctor;
use App\Models\Role;
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

    /**
     * Display a listing of the resource.
     *
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request)
    {
        $doctors = Doctor::query()
            ->where('hospital_id', $request->get('hospitalId'));

        if ($doctorName = $request->get('name')) {
            $doctors->where('name', 'LIKE', $doctorName .'%');
        }
        return $this->sendResponse($doctors->get(), 'List of doctors');
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
                'profession' => $request->get('profession'),
                'hospital_id' => $request->get('hospitalId'),
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (Doctor::query()->where('id', $id)->delete()) {
            return $this->sendResponse([], 'Doctor deleted successfully');
        }

        return $this->sendError('Doctor not found');
    }
}
