<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $name = request()->get('name');
        $type = request()->get('type');

        return [
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', 'max:255', Rule::unique('categories')
                ->where(function ($query) use($name, $type) {
                    return $query->where([
                        'name' => $name,
                        'type' => $type
                    ]);
                }),
            ],
        ];
    }
}
