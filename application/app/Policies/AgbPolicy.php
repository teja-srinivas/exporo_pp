<?php

namespace App\Policies;

class AgbPolicy extends BasePolicy
{
    const PERMISSION = 'manage agbs';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
