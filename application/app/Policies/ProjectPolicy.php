<?php

namespace App\Policies;

class ProjectPolicy extends BasePolicy
{
    const PERMISSION = 'management.projects';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
