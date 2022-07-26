<?php

declare(strict_types=1);

namespace App\Policies;

class InvestmentPolicy extends BasePolicy
{
    public const PERMISSION = 'management.investments';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
