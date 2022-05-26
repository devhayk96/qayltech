<?php

namespace App\Http\Requests\Patient;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'disability_date' => 'date',
            'disability_reason' => 'text',
            'disability_category' => 'string',
            'injury' => 'string|max:255',
            'workout_begin' => 'required|date',
            'is_individual' => 'required|boolean',
            'image' => 'required|string|max:255',
            'pdf' => 'required|string|max:255',
        ];
    }
}
