<?php

namespace App\Policies;

class ProjectPolicy extends BasePolicy
{
    const PERMISSION = 'manage projects';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
