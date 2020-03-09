<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events;
use App\Listeners;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Events\CommissionBonusUpdated::class => [
            Listeners\InvalidateCommissionsOnCommissionBonusChanges::class,
        ],
        Events\ContractSaving::class => [
            Listeners\AutoAcceptContracts::class,
        ],
        Events\ContractUpdated::class => [
            Listeners\TerminateOldContractOnApproval::class,
            Listeners\UnreleaseContractsOnApproval::class,
        ],
        Events\ProjectUpdated::class => [
            Listeners\InvalidateInvestmentCommissionsOnProjectChanges::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PasswordReset::class => [
            Listeners\TrackUserActivations::class,
        ],
        Events\SchemaUpdated::class => [
            Listeners\InvalidateInvestmentCommissionsOnSchemaChanges::class,
        ],
        Events\UserUpdated::class => [
            Listeners\SendUserAcceptOrRejectMailOnUpdate::class,
        ],
        Events\UserDetailsUpdated::class => [
            Listeners\UpdateBillingStatusOnUserDetailsUpdate::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
