<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AssignPatientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'patientId' => 'required|exists:countries,id',
            'doctorId' => 'required|exists:doctors,id',
            'deviceId' => 'required|exists:devices,id',
            'additionalInfos' => 'array',
        ];
    }
}
