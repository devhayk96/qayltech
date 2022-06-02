<?php

namespace App\Http\Requests\Device;

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
            'organizationId' => 'nullable|exists:organizations,id',
            'countryId' => 'required|exists:countries,id',
            'hospitalId' => 'nullable|exists:hospitals,id',
            'code' => 'required|string|unique:devices,code',
        ];

    }
}
