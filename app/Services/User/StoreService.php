<?php

namespace App\Services\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\BaseCreateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class StoreService extends BaseCreateService
{
    public function getModel() : Model
    {
        return new User();
    }

    public function getResourceData(): JsonResource
    {
        return new UserResource($this->model);
    }

    public function getCreatedData() : array
    {
        $password = Str::random(10);
        $this->data['password'] = $password;

        return $this->data;
    }
}
