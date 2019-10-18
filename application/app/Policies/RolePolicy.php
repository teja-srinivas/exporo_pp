<?php

declare(strict_types=1);

namespace App\Policies;

class RolePolicy extends BasePolicy
{
    const PERMISSION = 'management.authorization';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
