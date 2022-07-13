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
        $model = false;
        $relations = [];
        $roles = Role::ALL;

        if (current_user_role() == $roles['super_admin']) {
            return response()->json(current_user(current_user_role_name()));
        }

        unset($roles['super_admin']);
        unset($roles['hospital_patient']);

        foreach ($roles as $roleName => $roleId) {
            if (current_user_role() == $roleId) {
                $model = $this->getModel($roleName);
                $relations = $model::$relationNamesForProfileData;
            }
        }

        return response()->json(
            $model ?
                $model->where('user_id', current_user()->id)->first()->load($relations)
                : []
        );
    }
}
