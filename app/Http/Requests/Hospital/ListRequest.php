<?php

namespace App\Http\Requests\Hospital;

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
            'countryId' => 'nullable|numeric',
            'organizationId' => 'nullable|exists:organizations,id',
            'email' => 'nullable|string|email|max:90',
        ];
    }
}
