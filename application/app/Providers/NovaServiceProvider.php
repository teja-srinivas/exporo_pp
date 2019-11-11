<?php

declare(strict_types=1);

namespace App\Providers;

use App\Nova\Metrics;
use Laravel\Nova\Nova;
use Parental\Providers\NovaResourceProvider;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->app->register(NovaResourceProvider::class);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->register();
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
        // Do not use a custom gate, we use a permission system for this
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
