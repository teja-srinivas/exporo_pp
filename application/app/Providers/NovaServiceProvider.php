<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Nova\Metrics;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * @inheritdoc
     */
    public function boot()
    {
        parent::boot();

        // Adds the "viewNova" gate to all requests so we can add the button to the sidebar
        $this->gate();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function (User $user) {
            return $user->hasAnyRole([Role::ADMIN, Role::INTERNAL]);
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Metrics\UsersPerDay(),
        ];
    }
}
