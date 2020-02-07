<?php

declare(strict_types=1);

namespace App\Policies;

class AffiliateDashboardPolicy extends BasePolicy
{
    public const PERMISSION = 'management.affiliate.dashboard';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
