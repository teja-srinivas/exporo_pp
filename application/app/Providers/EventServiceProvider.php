<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\UserUpdated;
use App\Events\SchemaUpdated;
use App\Events\ProjectUpdated;
use App\Events\UserDetailsUpdated;
use App\Events\CommissionBonusUpdated;
use Illuminate\Auth\Events\Registered;
use App\Listeners\TrackUserActivations;
use Illuminate\Auth\Events\PasswordReset;
use App\Listeners\SendUserAcceptOrRejectMailOnUpdate;
use App\Listeners\UpdateBillingStatusOnUserDetailsUpdate;
use App\Listeners\InvalidateCommissionsOnCommissionBonusChanges;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\InvalidateInvestmentCommissionsOnSchemaChanges;
use App\Listeners\InvalidateInvestmentCommissionsOnProjectChanges;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CommissionBonusUpdated::class => [
            InvalidateCommissionsOnCommissionBonusChanges::class,
        ],
        ProjectUpdated::class => [
            InvalidateInvestmentCommissionsOnProjectChanges::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PasswordReset::class => [
            TrackUserActivations::class,
        ],
        SchemaUpdated::class => [
            InvalidateInvestmentCommissionsOnSchemaChanges::class,
        ],
        UserUpdated::class => [
            SendUserAcceptOrRejectMailOnUpdate::class,
        ],
        UserDetailsUpdated::class => [
            UpdateBillingStatusOnUserDetailsUpdate::class,
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
