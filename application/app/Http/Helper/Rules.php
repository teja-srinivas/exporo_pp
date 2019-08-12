<?php

namespace App\Http\Helper;

use Illuminate\Support\Arr;

class Rules
{
    /**
     * @param array|string $prefix
     * @param array $rules
     * @return array
     */
    public static function prefix($prefix, array $rules): array
    {
        $prefixed = [];

        foreach ($rules as $name => $rule) {
            $wrapped = Arr::wrap($rule);
            array_unshift($wrapped, ...Arr::wrap($prefix));
            $prefixed[$name] = $wrapped;
        }

        return $prefixed;
    }
}
