<?php

namespace App\Providers;

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
        Schema::defaultStringLength(191);

        // Accented card with its title and content in the body
        Blade::component('components.card', 'card');

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
