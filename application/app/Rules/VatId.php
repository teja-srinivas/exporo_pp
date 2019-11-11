<?php

declare(strict_types=1);

namespace App\Rules;

use Ibericode\Vat\Validator;
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
     * @param  string  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->validator->validateVatNumber($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ungültige oder nicht-existente USt-IdNr.';
    }
}
