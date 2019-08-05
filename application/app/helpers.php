<?php

use Illuminate\Support\Arr;

define('LEGACY_NULL', '1970-01-01 00:00:00');

if (! function_exists('format_money')) {
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

if (! function_exists('render_breadcrumps')) {
    /**
     * Renders a list of breadcrumps (link => display name).
     *
     * @param array $breadcrumps
     * @return string
     */
    function render_breadcrumps(array $breadcrumps): string
    {
        static $divider = '<span class="text-muted"> / </span>';

        $last = Arr::last($breadcrumps);

        return implode($divider, array_map(function ($name, $link) use ($last) {
            $class = $name === $last ? '' : 'text-muted';

            return empty($link) || is_numeric($link)
                ? "<span class='$class'>$name</span>"
                : "<a href='$link' class='$class'>$name</a>";
        }, $breadcrumps, array_keys($breadcrumps)));
    }
}

if (! function_exists('flash_success')) {
    /**
     * Flashes a success message (usually after saving some data).
     *
     * @param string $message
     * @return void
     */
    function flash_success(string $message = 'Eingaben wurden gespeichert'): void
    {
        session()->flash('status', $message);
    }
}
