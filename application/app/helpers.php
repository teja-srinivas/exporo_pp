<?php

use Illuminate\Support\Arr;

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

if (! function_exists('render_breadcrumbs')) {
    /**
     * Renders a list of breadcrumbs (link => display name).
     *
     * @param array $breadcrumbs
     * @return string
     */
    function render_breadcrumbs(array $breadcrumbs): string
    {
        static $divider = '<span class="text-muted"> / </span>';

        $last = Arr::last($breadcrumbs);

        return implode($divider, array_map(function ($name, $link) use ($last) {
            $class = $name === $last ? '' : 'text-muted';

            return empty($link) || is_numeric($link)
                ? "<span class='$class'>$name</span>"
                : "<a href='$link' class='$class'>$name</a>";
        }, $breadcrumbs, array_keys($breadcrumbs)));
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

if (! function_exists('new_relic_disable')) {
    /**
     * Disables injection of NewRelic tags and scripts when rendering an HTML response.
     *
     * @return void
     */
    function new_relic_disable(): void
    {
        if (extension_loaded('newrelic')) {
            newrelic_disable_autorum();
        }
    }
}
