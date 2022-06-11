<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ImageRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        preg_match("/\"data:image\\\\\\/png;base64,([^\"]+)/", $value, $matches, PREG_OFFSET_CAPTURE);

        return isset($matches[0]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.image');
    }
}
