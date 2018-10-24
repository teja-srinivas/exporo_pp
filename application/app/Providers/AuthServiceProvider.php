<?php

namespace App\Providers;

use App;
use App\Policies;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        App\Models\Agb::class => Policies\AgbPolicy::class,
        App\Models\Bill::class => Policies\BillPolicy::class,
        App\Models\Commission::class => Policies\BillPolicy::class, // TODO
        App\Models\Document::class => Policies\DocumentPolcy::class,
        App\Models\Permission::class => Policies\PermissionPolicy::class,
        App\Models\Project::class => Policies\ProjectPolicy::class,
        App\Models\Role::class => Policies\RolePolicy::class,
        App\Models\Schema::class => Policies\SchemaPolicy::class,
        App\Models\User::class => Policies\UserPolicy::class,
        App\Models\CommissionType::class => Policies\CommissionTypePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view partner dashboard', function (App\Models\User $user) {
            return $user->hasAnyRole(App\Models\Role::ADMIN, App\Models\Role::PARTNER);
        });
    }
}
