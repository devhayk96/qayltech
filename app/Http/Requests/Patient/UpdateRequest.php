<?php

namespace App\Http\Requests\Patient;

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
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'birthDate' => 'required|date',
            'disabilityDate' => 'date',
            'disabilityReason' => 'text',
            'disabilityCategory' => 'string',
            'injury' => 'string|max:255',
            'workoutBegin' => 'required|date',
            'isIndividual' => 'required|boolean',
            'image' => 'required|string|max:255',
            'pdf' => 'required|string|max:255',
        ];
    }
}
