<?php

namespace App\Http\Requests\Patient\AdditionalInfo;

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
            'countryId' => 'required|exists:countries,id',
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ];
    }
}
