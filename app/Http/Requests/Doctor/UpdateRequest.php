<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstName' => 'required|string|max:191',
            'lastName' => 'required|string|max:191',
            'profession' => 'required|string|max:191',
            'hospitalId' => 'required|exists:hospitals,id',
            'email' => 'required|unique:users,email'
        ];
    }
}
