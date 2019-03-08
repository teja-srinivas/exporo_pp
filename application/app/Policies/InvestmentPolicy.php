<?php

namespace App\Policies;

class InvestmentPolicy extends BasePolicy
{
    const PERMISSION = 'manage investments';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
