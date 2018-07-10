<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Database setup
        Schema::defaultStringLength(191);

        Relation::morphMap([
            \App\Investment::MORPH_NAME => \App\Investment::class,
            \App\Investor::MORPH_NAME => \App\Investor::class,
        ]);

        // Accented card with its title and content in the body
        Blade::component('components.card', 'card');

        // Add @timeago as we use it very often to display relative timestamps
        Blade::directive('timeago', function ($exp) {
            return "<?php echo \$__env->make('components.timeago', ['dateTime' => $exp]) ?>";
        });

        // Custom breadcrumps instead of the bootstrap component
        Blade::directive('breadcrumps', function ($exp) {
            return "<?php echo render_breadcrumps($exp); ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
