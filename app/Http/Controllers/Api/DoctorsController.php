<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Doctor\ListRequest;
use App\Http\Requests\Doctor\StoreRequest;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorsController extends BaseController
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
                'user_id' => $doctorUser->id
            ]);

            DB::commit();
            return $this->sendResponse($doctorUser, 'Doctor successfully created');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
