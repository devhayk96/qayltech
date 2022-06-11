<?php

namespace App\Http\Requests\Patient\AdditionalInfo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $key = request()->get('key');
        $value = request()->get('value');
        $patientId = request()->route('patient')->id;

        return [
            'key' => 'required|string|max:255',
            'workoutId' => 'nullable|exists:patient_workout_infos,id',
            'value' => [
                'required',
                'string',
                'max:191',
                Rule::unique('patient_additional_infos')
                    ->where(function ($query) use($key, $value, $patientId) {
                        return $query->where([
                            'key' => $key,
                            'value' => $value,
                            'patient_id' => $patientId
                        ]);
                    }),
                ]
        ];
    }
}
