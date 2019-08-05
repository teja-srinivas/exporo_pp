<?php

namespace App\Rules;

use Exception;
use FormulaInterpreter\Compiler;
use Illuminate\Contracts\Validation\Rule;

class Formula implements Rule
{
    /** @var Compiler */
    private $compiler;

    public function __construct(Compiler $compiler)
    {
        $this->compiler = $compiler;
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
        try {
            $this->compiler->compile($value);

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
