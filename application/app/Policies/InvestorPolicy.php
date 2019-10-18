<?php

declare(strict_types=1);

namespace App\Policies;

class InvestorPolicy extends BasePolicy
{
    const PERMISSION = 'management.investors';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
