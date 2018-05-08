<?php

namespace App\Providers;

use App\Role;
use Illuminate\Contracts\Auth\Access\Authorizable;
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
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Admins fall back to having all permissions by default
        Gate::before(function (Authorizable $user) {
            if (method_exists($user, 'hasRole') && $user->hasRole(Role::ADMIN)) {
                return true;
            }
        });
    }
}
