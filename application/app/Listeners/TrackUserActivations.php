<?php

namespace App\Listeners;

class TrackUserActivations
{
    public function handle()
    {
        session()->put('trackUserActivation', true);
    }
}
