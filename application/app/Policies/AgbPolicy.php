<?php

namespace App\Policies;

class AgbPolicy extends BasePolicy
{
    const PERMISSION = 'manage agbs';

    /**
     * AgbPolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
