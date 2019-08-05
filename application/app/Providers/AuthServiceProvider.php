<?php

namespace App\Providers;

use App\Models;
use App\Policies;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        Models\CommissionBonus::class => Policies\CommissionBonusPolicy::class,
        Models\CommissionType::class => Policies\CommissionTypePolicy::class,
        Models\Contract::class => Policies\ContractPolicy::class,
        Models\ContractTemplate::class => Policies\ContractTemplatePolicy::class,
        Models\Document::class => Policies\DocumentPolicy::class,
        Models\Investment::class => Policies\InvestmentPolicy::class,
        Models\Investor::class => Policies\InvestorPolicy::class,
        Models\Link::class => Policies\LinkPolicy::class,
        Models\Mailing::class => Policies\MailingPolicy::class,
        Models\Permission::class => Policies\PermissionPolicy::class,
        Models\Project::class => Policies\ProjectPolicy::class,
        Models\Role::class => Policies\RolePolicy::class,
        Models\Schema::class => Policies\SchemaPolicy::class,
        Models\User::class => Policies\UserPolicy::class,
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
