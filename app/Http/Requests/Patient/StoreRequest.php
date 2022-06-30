<?php

namespace App\Http\Requests\Patient;
use App\Rules\EmailRule;
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
            'organizationId' => 'nullable|exists:organizations,id',
            'hospitalId' => 'required_with:organizationId|exists:hospitals,id',
            'doctorId' => 'required_with:hospitalId|exists:doctors,id',
            'deviceId' => 'nullable|exists:devices,id',
            'firstName' => 'required|string|max:191',
            'lastName' => 'required|string|max:191',
            'email' => ['required', new EmailRule(), 'unique:users,email'],
            'birthDate' => ['required', 'date_format:Y-m-d', 'before_or_equal:'. date('Y-m-d', strtotime('-6 years'))],
            'disabilityDate' => 'nullable|date',
            'disabilityReason' => 'nullable|string',
            'disabilityCategory' => 'nullable|string',
            'injury' => 'nullable|string|max:191',
            'workoutBegin' => 'nullable|date',
            'image' => [
                'sometimes',
                'image',
                'mimes:png,jpg,jpeg,webp',
//                'string',
//                'base64image'
            ],
            'pdf' => ['nullable', 'mimes:pdf', 'max:1024'],
        ];
    }
}
