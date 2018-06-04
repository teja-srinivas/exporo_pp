<?php

namespace App\Providers;

use App\Agb;
use App\Document;
use App\Permission;
use App\Policies\AgbPolicy;
use App\Policies\DocumentPolcy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Role;
use App\User;
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
        Agb::class => AgbPolicy::class,
        Document::class => DocumentPolcy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view partner dashboard', function (User $user) {
            return $user->hasAnyRole(Role::ADMIN, Role::PARTNER);
        });
    }
}
