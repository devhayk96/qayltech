<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Patient\StoreRequest;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doctor_id = $request->doctor_id;
        $query = Patient::all()->where('doctor_id', '=', $doctor_id);
        if ($request->is_individual){
            $is_individual = $request->is_individual;
            $query = $query->where('is_individual', '=', $is_individual);
        }
        return $query;
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
     * Store a newly created patient in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $patientUser = User::create([
                'role_id' => Role::ALL['patient'],
                'name' => $request->get('first_name'),
                'email' => $request->get('email'),
                'password' => Hash::make(Str::random(8))
            ]);

            $patient = Patient::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'birth_date' => $request->get('birth_date'),
                'disability_date' => $request->get('disability_date'),
                'disability_reason' => $request->get('disability_reason'),
                'disability_category' => $request->get('disability_category'),
                'workout_begin' => $request->get('workout_begin'),
                'injury' => $request->get('injury'),
                'is_individual' => $request->get('is_individual'),
                'image' => $request->get('image'),
                'pdf' => $request->get('pdf'),
                'user_id' => $patientUser->id
            ]);

            DB::commit();
            return $this->sendResponse($patientUser, 'Hospital user successfully created');
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
