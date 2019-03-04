<?php

namespace App\Providers;

use App\Models\UserDetails;
use App\Models\User;
use App\Observers\UserDetailsObserver;
use App\Observers\UserObserver;
use FormulaInterpreter\Compiler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
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
            // DO NOT CHANGE THE ORDER OF THIS MAP - STUFF _WILL_ BREAK
            \App\Models\Investment::MORPH_NAME => \App\Models\Investment::class,
            \App\Models\Investment::LEGACY_MORPH_NAME => \App\Models\Investment::class,
            \App\Models\Investor::MORPH_NAME => \App\Models\Investor::class,
            \App\Models\Commission::TYPE_CORRECTION => \App\Models\Commission::class,
        ]);

        // Accented card with its title and content in the body
        Blade::component('components.card', 'card');

        // Custom breadcrumps instead of the bootstrap component
        Blade::directive('breadcrumps', function ($exp) {
            return "<?php echo render_breadcrumps($exp); ?>";
        });

        Collection::macro('sortNatural', function ($callback) {
            /** @var Collection $this */
            return $this->sortBy($callback, SORT_NATURAL | SORT_FLAG_CASE);
        });

        User::observe(UserObserver::class);
        UserDetails::observe(UserDetailsObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Compiler::class, function () {
            $compiler = new Compiler();
            $compiler->functionCommandFactory->registerFunction('min', 'min');
            $compiler->functionCommandFactory->registerFunction('max', 'max');

            return $compiler;
        });
    }
}
