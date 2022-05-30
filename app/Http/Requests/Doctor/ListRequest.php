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
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'hospitalId' => 'required|exists:hospitals,id',
            'email' => 'required|unique:users',
        ];
    }
}
