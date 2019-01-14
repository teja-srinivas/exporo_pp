<?php

namespace App\Providers;

use App\Http\ViewComposers\BundleEditorComposer;
use App\Http\ViewComposers\RegisterComposer;
use App\Http\ViewComposers\SidebarComposer;
use App\Http\ViewComposers\UserTableComposer;
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
        View::composer('auth.partials.register', RegisterComposer::class);
        View::composer('components.bundle-editor', BundleEditorComposer::class);
        View::composer('layouts.sidebar', SidebarComposer::class);
        View::composer('users.partials.table', UserTableComposer::class);
    }
}
