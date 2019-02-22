<?php

namespace App\Policies;

class RolePolicy extends BasePolicy
{
    const PERMISSION = 'manage authorization';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
