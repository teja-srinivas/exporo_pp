<?php

namespace App\Policies;

class InvestorPolicy extends BasePolicy
{
    const PERMISSION = 'manage investors';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
