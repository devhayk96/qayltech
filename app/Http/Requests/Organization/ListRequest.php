<?php

namespace App\Http\Requests\Organization;

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
            'countryId' => 'nullable|exists:countries,id',
            'name' => 'nullable|string|max:90',
            'email' => 'nullable|string|email|max:90',
        ];
    }
}
