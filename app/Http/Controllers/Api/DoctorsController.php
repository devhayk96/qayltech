<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Doctor\StoreRequest;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hospital_id = $request->hospital_id;
        $doctors = Doctor::all()->where('hospital_id', '=', $hospital_id);
        return $doctors;
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
     * Store a newly created doctor in database.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $doctorUser = User::create([
                'role_id' => Role::ALL['doctor'],
                'name' => $request->get('first_name'),
                'email' => $request->get('email'),
                'password' => Hash::make(Str::random(8))
            ]);

            $doctor = Doctor::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'profession' => $request->get('profession'),
                'hospital_id' => $request->get('hospital_id'),
                'user_id' => $doctorUser->id
            ]);

            DB::commit();
            return $this->sendResponse($doctorUser, 'Doctor user successfully created');
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
