<?php

namespace App\Policies;

class CommissionBonusPolicy extends BasePolicy
{
    const PERMISSION = 'manage commission bonuses';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
