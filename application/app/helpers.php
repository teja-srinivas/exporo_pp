<?php

if (! function_exists('now')) {
    /**
     * Replaces Laravel's default now() function
     * to return a translatable carbon instance.
     *
     * @return \Jenssegers\Date\Date
     */
    function now()
    {
        return \Jenssegers\Date\Date::now();
    }
}

if (!function_exists('format_money')) {
    /**
     * Get a formatted number.
     *
     * @param float $amount
     * @param int $decimals
     * @param string $pattern
     * @return string
     */
    function format_money($amount, $decimals = 2, $pattern = '%s €'): string
    {
        return sprintf($pattern, number_format($amount, $decimals, ',', '.'));
    }
}
