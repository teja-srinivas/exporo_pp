<?php

namespace App\Providers;

use App\Models;
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
        Models\Agb::class => Policies\AgbPolicy::class,
        Models\Banner::class => Policies\BannerPolicy::class,
        Models\BannerSet::class => Policies\BannerSetPolicy::class,
        Models\Bill::class => Policies\BillPolicy::class,
        Models\BonusBundle::class => Policies\BonusBundlePolicy::class,
        Models\Commission::class => Policies\BillPolicy::class, // TODO
        Models\Document::class => Policies\DocumentPolicy::class,
        Models\Link::class => Policies\LinkPolicy::class,
        Models\Mailing::class => Policies\MailingPolicy::class,
        Models\Permission::class => Policies\PermissionPolicy::class,
        Models\Project::class => Policies\ProjectPolicy::class,
        Models\Role::class => Policies\RolePolicy::class,
        Models\Schema::class => Policies\SchemaPolicy::class,
        Models\User::class => Policies\UserPolicy::class,
        Models\CommissionType::class => Policies\CommissionTypePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
