<?php

namespace App\Listeners;

use App\Models\Commission;
use App\Events\CommissionBonusUpdated;

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
        if ($event->commissionBonus->contract_id === null) {
            return;
        }

        Commission::query()
            ->where('user_id', $event->commissionBonus->contract->user_id)
            ->isRecalculatable()
            ->delete();
    }
}
