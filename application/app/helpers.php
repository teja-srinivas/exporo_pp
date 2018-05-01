<?php

if (! function_exists('now'))
{
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

if (!function_exists('number')) {
    /**
     * Get a formatted number.
     *
     * @param float $amount
     * @param int $decimals
     * @param string $pattern
     * @return string
     */
    function formatMoney($amount, $decimals = 2, $pattern = '%s €')
    {
        return sprintf($pattern, number_format($amount, $decimals, ',', '.'));
    }
}
