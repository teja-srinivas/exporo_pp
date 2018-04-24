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
