<?php

namespace App\Policies;

class CommissionBonusPolicy extends BasePolicy
{
    const PERMISSION = 'management.commission-bonuses';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
