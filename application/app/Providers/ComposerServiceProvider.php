<?php

namespace App\Providers;

use App\Http\ViewComposers as C;
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
        View::composer('affiliate.links.partials.dashboard', C\LinkDashboardComposer::class);
        View::composer('auth.partials.register', C\RegisterComposer::class);
        View::composer('components.bundle-editor', C\BundleEditorComposer::class);
        View::composer('layouts.sidebar', C\SidebarComposer::class);
        View::composer('users.partials.table', C\UserTableComposer::class);
    }
}
