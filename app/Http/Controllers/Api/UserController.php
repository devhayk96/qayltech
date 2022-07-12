<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Organization;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile()
    {
        $user = User::query()->where('id', current_user()->id);
        $relations = [];

        if (current_user_role() == Role::ALL['country']) {
            $relations = [
                'organizations',
                'hospitals',
                'doctors',
                'patients',
            ];
        } elseif (current_user_role() == Role::ALL['organization']) {
            $relations = [
                'hospitals',
                'doctors',
                'patients',
            ];
        } elseif (current_user_role() == Role::ALL['hospital']) {
            $relations = [
                'doctors',
                'patients',
            ];
        } elseif (current_user_role() == Role::ALL['doctor']) {
            $relations = [
                'patients'
            ];
        } else {
            return current_user(current_user_role_name());
        }

        return response()->json($user->with($relations)->first());
    }
}
