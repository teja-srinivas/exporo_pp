<?php

declare(strict_types=1);

namespace App\Policies;

class ProjectPolicy extends BasePolicy
{
    public const PERMISSION = 'management.projects';

    public function __construct()
    {
        parent::__construct(self::PERMISSION);
    }
}
