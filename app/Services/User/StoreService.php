<?php

namespace App\Services\User;

use App\Http\Resources\UserStoreResource;
use App\Models\Role;
use App\Models\User;
use App\Services\BaseCreateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreService extends BaseCreateService
{
    protected $role;

    public function __construct(Request $request)
    {
        $password = generate_user_password();
        $request->merge(['password' => $password]);

        /* need to get the password before hashing */
        request()->merge(['password' => $password]);

        $this->role = Role::find($request->role_id);

        parent::__construct($request);
    }

    public function getModel() : Model
    {
        return new User();
    }

    public function getResourceData(): JsonResource
    {
        return new UserStoreResource($this->model);
    }

    public function create(): JsonResource
    {
        $this->model->fill(
            $this->getCreatedData()
        );

        if ($this->model->save()) {
            $this->model->assignRole($this->role);
            return $this->getResourceData();
        }

        return new JsonResource([]);
    }

    public function getCreatedData() : array
    {
        unset($this->data['role_id']);
        return $this->data;
    }
}
