<?php

declare(strict_types=1);

namespace App\Traits;

use Spatie\Permission\Traits\HasRoles as SpatieRoles;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Extension to the spatie permission package
 * to add timestamp support for the roles.
 */
trait HasRoles
{
    use SpatieRoles {
        roles as parentRoles;
        permissions as parentPermissions;
    }

    public function roles(): MorphToMany
    {
        return $this->parentRoles()->withTimestamps();
    }

    public function permissions(): MorphToMany
    {
        return $this->parentPermissions()->withTimestamps();
    }
}
