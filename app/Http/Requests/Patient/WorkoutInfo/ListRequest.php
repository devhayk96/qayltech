<?php

namespace App\Http\Requests\Patient\WorkoutInfo;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deviceId' => 'nullable|exists:devices,id',
            'game' => 'nullable|string',
        ];
    }
}
