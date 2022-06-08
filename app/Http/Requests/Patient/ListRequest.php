<?php

namespace App\Http\Requests\Patient;

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
            'countryId' => 'required|exists:countries,id',
            'organizationId' => 'nullable|exists:organizations,id',
            'hospitalId' => 'nullable|exists:hospitals,id',
            'isIndividual' => 'nullable|boolean',
            'doctorId' => 'nullable|exists:doctors,id',
            'email' => 'nullable|string|email|max:90',
        ];
    }
}
