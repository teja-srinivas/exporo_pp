<?php

namespace App\Helper;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class Rules
{
    /**
     * @param  array|string  $prefix
     * @param  array  $rules
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
