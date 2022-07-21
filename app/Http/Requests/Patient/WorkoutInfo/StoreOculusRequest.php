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
            'deviceCode' => 'required|exists:devices,code',
            'additionalInfos' => 'required|string',
            'status' => 'required|in:'. implode(",",[WorkoutStatuses::IN_PROGRESS, WorkoutStatuses::FINISH])
        ];
    }
}



