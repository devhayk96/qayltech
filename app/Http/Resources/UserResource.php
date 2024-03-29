<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'user_id' => $this->id,
            'role' => $this->role->name,
            'name' => $this->name,
            'email' => $this->email,
            'password' => is_super_admin() ? $this->password_unhashed : null,
        ];
    }
}
