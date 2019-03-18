<?php

namespace App\Policies;

class InvestmentPolicy extends BasePolicy
{
    const PERMISSION = 'management.investments';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
