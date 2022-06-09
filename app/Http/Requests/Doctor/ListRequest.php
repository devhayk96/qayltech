<?php

namespace App\Http\Requests\Doctor;

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
            'hospitalId' => 'nullable|exists:hospitals,id',
            'countryId' => 'required|exists:countries,id',
            'organizationId' => 'nullable|exists:organizations,id',
            'name' => 'nullable|string|max:90',
            'email' => 'nullable|string|email|max:90',
            'profession' => 'nullable|string|max:90',
        ];
    }
}
