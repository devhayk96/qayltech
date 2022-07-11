<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Organization;
use App\Models\Patient;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile()
    {
        $relations = [];

        if(current_user_role() == Role::ALL['super_admin']){

            return current_user(current_user_role_name());
        }

        if(current_user_role() == Role::ALL['country']){
           $user = auth()->id();
            $countrId = Country::query()->where('user_id', $user)->pluck('id')->first();

            $organizations = Organization::query()->where('country_id', $countrId)->get();
            $hospitals = Hospital::query()->where('country_id', $countrId)->get();
            $doctors = Doctor::query()->where('country_id', $countrId)->get();
            $patients = Patient::query()->where('country_id', $countrId)->get();

            $relations = [
                'orgazniations' => $organizations,
                'hospitals' => $hospitals,
                'doctors' => $doctors,
                'patients' => $patients,
            ];

           return response($relations);
        }

        if(current_user_role() == Role::ALL['organization']){
            $user = auth()->id();
            $organizationId = Organization::query()->where('user_id', $user)->pluck('id')->first();

            $hospitals = Hospital::query()->where('organization_id', $organizationId)->get();
            $doctors = Doctor::query()->where('organization_id', $organizationId)->get();
            $patients = Patient::query()->where('organization_id', $organizationId)->get();

            $relations = [
              'hospitals' => $hospitals,
              'doctors' => $doctors,
              'patients' => $patients,
            ];

            return response($relations);
        }

        if(current_user_role() == Role::ALL['hospital']){
            $user = auth()->id();
            $hospitalId = Hospital::query()->where('user_id', $user)->pluck('id')->first();

            $doctors = Doctor::query()->where('hospital_id', $hospitalId)->get();
            $patients = Patient::query()->where('hospital_id', $hospitalId)->get();

            $relations = [
                'doctors' => $doctors,
                'patients' => $patients,
            ];

            return response($relations);
        }

        if(current_user_role() == Role::ALL['doctor']){
            $user = auth()->id();
            $doctor = Doctor::query()->where('user_id', $user)->first();
            $patients = $doctor->patients;


            $relations = [
                'patients' => $patients
            ];

            return response($relations);
        }

        if(current_user_role() == Role::ALL['patient'] || current_user_role() == Role::ALL['hospital_patient']){

            return current_user(current_user_role_name());
        }

    }
}
