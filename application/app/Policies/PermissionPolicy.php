<?php

namespace App\Policies;

class PermissionPolicy extends BasePolicy
{
    /**
     * PermissionPolicy constructor.
     */
    public function __construct()
    {
        // We have the same permissions as roles
        parent::__construct(RolePolicy::PERMISSION);
    }
}
