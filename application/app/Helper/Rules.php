<?php

namespace App\Helper;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class Rules
{
    /**
     * @param  array  $rules
     * @param  array|string  $prepend
     * @return array
     */
    public static function prepend(array $rules, $prepend): array
    {
        $prefixed = [];

        foreach ($rules as $name => $rule) {
            $wrapped = Arr::wrap($rule);
            array_unshift($wrapped, ...Arr::wrap($prepend));
            $prefixed[$name] = $wrapped;
        }

        return $prefixed;
    }

    /**
     * Returns only the rules for which the currently logged in user
     * has the given permission for.
     *
     * @param  array  $rules
     * @return array
     */
    public static function byPermission(array $rules): array
    {
        $passed = [];

        foreach ($rules as $permission => $contents) {
            if (Gate::check($permission)) {
                $passed += $contents;
            }
        }

        return $passed;
    }
}
