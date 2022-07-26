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
            'game' => 'required|string',
            'walk_count' => 'required|string',
            'steps_count' => 'required|string',
            'steps_opening' => 'required|string',
            'speed' => 'required|string',
            'passed_way' => 'required|string',
            'calories' => 'required|string',
            'spent_time' => 'required|string',
            'key1' => 'nullable|string',
            'key2' => 'nullable|string',
            'key3' => 'nullable|string',
            'status' => 'required|in:'. implode(",",[WorkoutStatuses::IN_PROGRESS, WorkoutStatuses::FINISH])
        ];
    }
}



