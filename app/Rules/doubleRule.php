<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class doubleRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        $out = explode('.', $value);

        if (count($out) == 2){
            if (strlen($out[1]) > 2){
                if ((int)$out[1] == 0)
                    return false;
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute must be double  and content 4 digit after .";
    }
}
