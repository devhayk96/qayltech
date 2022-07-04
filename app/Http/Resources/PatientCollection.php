<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;

class PatientCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function($data) {
            return [
                'id'                  => $data->id,
                'user_id'             => $data->user_id,
                'country_id'          => $data->country_id,
                'organization_id'     => $data->organization_id,
                'hospital_id'         => $data->hospital_id,
                'first_name'          => $data->first_name,
                'last_name'           => $data->last_name,
                'birth_date'          => Carbon::parse($data->birth_date)->toDateTimeString(),
                'disability_date'     => Carbon::parse($data->disability_date)->toDateTimeString(),
                'disability_reason'   => $data->disability_reason,
                'disability_category' => $data->disability_category,
                'injury'              => $data->injury,
                'workout_begin'       => $data->workout_begin,
                'is_individual'       => $data->is_individual,
                'image_path'          => $data->imagePath,
                'pdf_path'            => $data->pdfPath,
                'created_at'          => Carbon::parse($data->created_at)->toDateTimeString(),
                'updated_at'          => $data->updated_at ? Carbon::parse($data->updated_at)->toDateTimeString() : null,
                'deleted_at'          => $data->deleted_at ? Carbon::parse($data->deleted_at)->toDateTimeString() : null,
            ];
        });
    }
}
