<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use MathParser\StdMathParser;

class Formula implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            (new StdMathParser())->parse($value);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ungültige Formel.';
    }
}
