<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models;
use App\Policies;
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
        Models\Embed::class => Policies\EmbedPolicy::class,
        Models\Banner::class => Policies\BannerPolicy::class,
        Models\BannerSet::class => Policies\BannerSetPolicy::class,
        Models\Bill::class => Policies\BillPolicy::class,
        Models\Commission::class => Policies\CommissionPolicy::class,
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
