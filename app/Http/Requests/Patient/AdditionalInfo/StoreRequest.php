<?php

namespace App\Http\Requests\Patient\AdditionalInfo;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(StoreRequest $request)
    {
        return [
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'mobile_no' => 'unique:key,value,'.request('patient_id')
        ];
    }
}
