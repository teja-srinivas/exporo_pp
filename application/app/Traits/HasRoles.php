<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles as SpatieRoles;

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

    public function roles(): BelongsToMany
    {
        return $this->parentRoles()->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->parentPermissions()->withTimestamps();
    }
}
