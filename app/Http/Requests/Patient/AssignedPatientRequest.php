<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AssignedPatientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'patient_id' => 'required|exists:countries,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'additionalInfos' => 'array',
            'additionalInfos.key' => 'max:191',
            'additionalInfos.value' => 'max:191',
        ];
    }
}
