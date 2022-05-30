<?php

namespace App\Http\Requests\Device;

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
            'organizationId' => 'nullable|exists:organizations,id',
            'hospitalId' => 'nullable|exists:hospitals,id',
            'code' => 'required|string|unique:devices,code',
        ];
    }
}
