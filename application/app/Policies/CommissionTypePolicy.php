<?php

namespace App\Policies;

class CommissionTypePolicy extends BasePolicy
{
    const PERMISSION = 'management.commission-types';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
