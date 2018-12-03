<?php

namespace App\Events;

use App\Models\CommissionBonus;
use Illuminate\Queue\SerializesModels;

class CommissionBonusUpdated
{
    use SerializesModels;

    /**
     * @var CommissionBonus
     */
    public $commissionBonus;

    /**
     * Create a new event instance.
     *
     * @param CommissionBonus $commissionBonus
     */
    public function __construct(CommissionBonus $commissionBonus)
    {
        $this->commissionBonus = $commissionBonus;
    }
}
