<?php

namespace App\Policies;

class ProvisitionTypePolicy extends BasePolicy
{
    const PERMISSION = 'manage provisionTypes';

    /**
     * AgbPolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
