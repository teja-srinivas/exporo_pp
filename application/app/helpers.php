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

if (!function_exists('render_breadcrumps')) {
    /**
     * Renders a list of breadcrumps (link => display name).
     *
     * @param array $breadcrumps
     * @return string
     */
    function render_breadcrumps(array $breadcrumps): string
    {
        static $divider = '<span class="text-muted"> / </span>';

        return join($divider, array_map(function ($name, $link) {
            return empty($link) ? $name : "<a href='$link' class='text-muted'>$name</a>";
        }, $breadcrumps, array_keys($breadcrumps)));
    }
}
