<?php

declare(strict_types=1);

namespace App\Rules;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $util = PhoneNumberUtil::getInstance();
            $number = $util->parse($value, 'DE');

            return $util->isValidNumber($number);
        } catch (NumberParseException $e) {
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
        return 'Ungültige Telefonnummer im deutschen Raum.';
    }
}
