<?php

namespace App\Policies;

class AgbPolicy extends BasePolicy
{
    const PERMISSION = 'management.agbs';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
