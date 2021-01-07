<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GermanUrlRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $url = str_replace(["ä", "ö", "ü"], ["ae", "oe", "ue"], $value);

        return preg_match('/^https?:\/\//', $url) && filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public function message(): string
    {
        return 'Ungültige URL!';
    }
}
