<?php

namespace App\Policies;

class CommissionTypePolicy extends BasePolicy
{
    const PERMISSION = 'manage commission types';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
