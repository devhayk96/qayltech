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
            'hospitalId' => 'required|exists:hospitals,id',
            'name' => 'nullable|string|max:90',
            'email' => 'required|unique:users',
        ];
    }
}
