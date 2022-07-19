<?php

namespace App\Http\Requests\Patient\WorkoutInfo;

use App\Enums\WorkoutStatuses;
use Illuminate\Foundation\Http\FormRequest;

class StoreOculusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deviceId' => 'required|exists:devices,id',
            'additionalInfos' => 'required|array',
            'status' => 'required|in:'. implode(",",[WorkoutStatuses::IN_PROGRESS, WorkoutStatuses::FINISH])
        ];
    }
}



