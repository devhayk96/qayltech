<?php

namespace App\Http\Requests\Hospital;

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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'categoryId' => 'required|exists:categories,id',
            'organizationId' => 'required|exists:organizations,id',
            'countryId' => 'required|exists:countries,id',
            'email' => 'required|unique:users',
        ];
    }
}
