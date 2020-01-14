<?php

declare(strict_types=1);

namespace App\Policies;

class DashboardPolicy extends BasePolicy
{
    public const PERMISSION = 'management.dashboard';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
