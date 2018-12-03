<?php

namespace App\Providers;

use App\Events\CommissionBonusUpdated;
use App\Events\ProjectUpdated;
use App\Events\SchemaUpdated;
use App\Listeners\InvalidateCommissionsOnCommissionBonusChanges;
use App\Listeners\InvalidateInvestmentCommissionsOnProjectChanges;
use App\Listeners\InvalidateInvestmentCommissionsOnSchemaChanges;
use Illuminate\Auth\Events\Registered;
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
        CommissionBonusUpdated::class => [
            InvalidateCommissionsOnCommissionBonusChanges::class,
        ],
        ProjectUpdated::class => [
            InvalidateInvestmentCommissionsOnProjectChanges::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SchemaUpdated::class => [
            InvalidateInvestmentCommissionsOnSchemaChanges::class,
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

        //
    }
}
