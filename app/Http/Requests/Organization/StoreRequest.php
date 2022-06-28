<?php

namespace App\Http\Requests\Organization;

use App\Rules\EmailRule;
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
            'name' => 'required|string|max:191',
            'email' => ['required', new EmailRule(), 'unique:users,email'],
            'address' => 'required|string|max:191',
            'categoryId' => 'required|exists:categories,id',
            'countryId' => 'required|exists:countries,id',
        ];
    }
}
