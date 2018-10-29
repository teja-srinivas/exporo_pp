<?php

namespace App\Providers;

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
            \App\Models\Investment::MORPH_NAME => \App\Models\Investment::class,
            \App\Models\Investor::MORPH_NAME => \App\Models\Investor::class,
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

        // Chunking that does not break with where clauses
        Builder::macro('chunkSimple', function (int $size, callable $callable) {
            $page = 1;

            while (($chunk = $this->limit($size)->get())->count() > 0) {
                if ($callable($chunk, $page++) === false) {
                    return false;
                }

                if ($chunk->count() < $size) {
                    break;
                }
            }

            return true;
        });

        Collection::macro('sortNatural', function ($callback) {
            return $this->sortBy($callback, SORT_NATURAL | SORT_FLAG_CASE);
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
