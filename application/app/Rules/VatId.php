<?php

namespace App\Rules;

use DvK\Vat\Validator;
use Illuminate\Contracts\Validation\Rule;

class VatId implements Rule
{
    /** @var Validator */
    private $validator;


    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
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
        return $this->validator->validateFormat($value);
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
