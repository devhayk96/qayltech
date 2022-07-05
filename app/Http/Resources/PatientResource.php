<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PatientResource extends JsonResource
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'country_id' => $this->country_id,
            'organization_id' => $this->organization_id,
            'hospital_id' => $this->hospital_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->user->email,
            'password' => is_super_admin() ? $this->user->password_unhashed : null,
            'birth_date' => Carbon::parse($this->birth_date)->toDateTimeString(),
            'disability_date' => Carbon::parse($this->disability_date)->toDateTimeString(),
            'disability_reason' => $this->disability_reason,
            'disability_category' => $this->disability_category,
            'injury' => $this->injury,
            'workout_begin' => $this->workout_begin,
            'is_individual' => $this->is_individual,
            'image_path' => $this->imagePath,
            'pdf_path' => $this->pdfPath,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->toDateTimeString() : null,
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->toDateTimeString() : null,
        ];
    }
}
