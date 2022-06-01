<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'countryId' => 'required|exists:countries,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'hospital_id' => 'required_with:organization_id|exists:hospitals,id',
            'doctor_id' => 'required_with:hospital_id|exists:doctors,id',
            'firstName' => 'required|string|max:191',
            'lastName' => 'required|string|max:191',
            'email' => 'required|unique:users,email',
            'birthDate' => ['required', 'date_format:Y-m-d', 'before_or_equal:'. date('Y-m-d', strtotime('-6 years'))],
            'disabilityDate' => 'nullable|date',
            'disabilityReason' => 'nullable|string',
            'disabilityCategory' => 'nullable|string',
            'injury' => 'nullable|string|max:191',
            'workoutBegin' => 'nullable|date',
            'image' => ['nullable', 'mimes:jpg,jpeg,png,bmp,tiff', 'max:4096'],
            'pdf' => ['nullable', 'mimes:pdf', 'max:1024'],
        ];
    }
}
