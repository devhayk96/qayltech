<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;

class CountryCollection extends ResourceCollection
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
                'name'                => $data->name,
                'created_at'          => Carbon::parse($data->created_at)->toDateTimeString(),
                'updated_at'          => $data->updated_at ? Carbon::parse($data->updated_at)->toDateTimeString() : null,
                'deleted_at'          => $data->deleted_at ? Carbon::parse($data->deleted_at)->toDateTimeString() : null,
            ];
        });
    }
}
