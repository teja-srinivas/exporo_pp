<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models;
use App\Observers\UserObserver;
use FormulaInterpreter\Compiler;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use App\Observers\UserDetailsObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\CalculateCommissionsService;
use Illuminate\Database\Eloquent\Relations\Relation;

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
            // DO NOT CHANGE THE ORDER OF THIS MAP - STUFF _WILL_ BREAK
            Models\Investment::MORPH_NAME => Models\Investment::class,
            Models\Investment::LEGACY_MORPH_NAME => Models\Investment::class,
            Models\Investor::MORPH_NAME => Models\Investor::class,
            Models\Commission::TYPE_CORRECTION => Models\Commission::class,
            Models\BannerLink::MORPH_NAME => Models\BannerLink::class,
            Models\Link::MORPH_NAME => Models\Link::class,
            Models\Campaign::MORPH_NAME => Models\Campaign::class,
        ]);

        // Accented card with its title and content in the body
        Blade::component('components.card', 'card');

        // Custom breadcrumbs instead of the bootstrap component
        Blade::directive('breadcrumbs', static function ($exp) {
            return "<?php echo render_breadcrumbs($exp); ?>";
        });

        Collection::macro('sortNatural', function ($callback) {
            /** @var Collection $this */

            return $this->sortBy($callback, SORT_NATURAL | SORT_FLAG_CASE);
        });

        Models\User::observe(UserObserver::class);
        Models\UserDetails::observe(UserDetailsObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CalculateCommissionsService::class);

        $this->app->singleton(Compiler::class, static function () {
            $compiler = new Compiler();
            $compiler->functionCommandFactory->registerFunction('min', 'min');
            $compiler->functionCommandFactory->registerFunction('max', 'max');

            return $compiler;
        });
    }
}
