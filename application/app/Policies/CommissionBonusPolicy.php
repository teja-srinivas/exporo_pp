<?php

declare(strict_types=1);

namespace App\Policies;

class CommissionBonusPolicy extends BasePolicy
{
    public const PERMISSION = 'management.commission-bonuses';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
