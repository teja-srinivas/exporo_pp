<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\SidebarComposer;
use App\Http\ViewComposers\RegisterComposer;
use App\Http\ViewComposers\UserTableComposer;
use App\Http\ViewComposers\BundleEditorComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Add all view composers.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('auth.partials.register', RegisterComposer::class);
        View::composer('components.bundle-editor', BundleEditorComposer::class);
        View::composer('layouts.sidebar', SidebarComposer::class);
        View::composer('users.partials.table', UserTableComposer::class);
    }
}
