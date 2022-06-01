<?php

namespace App\Services\User;

use App\Http\Resources\UserStoreResource;
use App\Models\User;
use App\Services\BaseCreateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreService extends BaseCreateService
{
    public function __construct(Request $request)
    {
        $password = generate_user_password();
        $request->merge(['password' => $password]);
        request()->merge(['password' => $password]);

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
}
