<?php

namespace App\Listeners;

use App\Events\CommissionBonusUpdated;
use App\Models\Commission;

class InvalidateCommissionsOnCommissionBonusChanges
{
    /**
     * Handle the event.
     *
     * @param  CommissionBonusUpdated  $event
     * @return void
     */
    public function handle(CommissionBonusUpdated $event)
    {
        if ($event->commissionBonus->user_id === 0) {
            return;
        }

        Commission::query()
            ->where('user_id', $event->commissionBonus->user_id)
            ->isOpen()
            ->delete();
    }
}
