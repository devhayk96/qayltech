<?php

namespace App\Http\Requests\Country;

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
            'name' => ['required', 'unique:countries,name', 'alpha', 'min:4', 'max:80'],
            'email' => ['required', 'unique:users,email', new EmailRule()],
        ];
    }
}
