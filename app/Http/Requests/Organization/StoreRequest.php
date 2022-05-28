<?php

namespace App\Http\Requests\Organization;

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
            'email' => 'required|unique:users',
            'address' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'country_id' => 'required|exists:countries,id',
        ];
    }
}
