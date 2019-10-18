<?php

declare(strict_types=1);

namespace App\Listeners;

class TrackUserActivations
{
    public function handle()
    {
        session()->put('trackUserActivation', true);
    }
}
