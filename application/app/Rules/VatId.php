<?php

namespace App\Rules;

use DvK\Laravel\Vat\Facades\Validator;
use Illuminate\Contracts\Validation\Rule;

class VatId implements Rule
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
        return Validator::validateFormat($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ungültige USt-IdNr.';
    }
}
