<?php

namespace App\Policies;

class CommissionPolicy extends BasePolicy
{
    const PERMISSION = 'management.commissions';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
