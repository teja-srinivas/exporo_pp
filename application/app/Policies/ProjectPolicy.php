<?php

namespace App\Policies;

class ProjectPolicy extends BasePolicy
{
    const PERMISSION = 'manage projects';

    /**
     * AgbPolicy constructor.
     */
    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
