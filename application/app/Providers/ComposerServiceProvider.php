<?php

namespace App\Providers;

use App\Http\ViewComposers\SidebarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Add all view composers.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.sidebar', SidebarComposer::class);
    }
}
