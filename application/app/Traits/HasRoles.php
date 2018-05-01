<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Permission\Traits\HasRoles as SpatieRoles;

/**
 * Extension to the spatie permission package
 * to add timestamp support for the roles.
 *
 * @package App\Traits
 */
trait HasRoles
{
    use SpatieRoles {
        roles as parentRoles;
        permissions as parentPermissions;
    }

    /**
     * @inheritdoc
     */
    public function roles(): MorphToMany
    {
        return $this->parentRoles()->withTimestamps();
    }

    /**
     * @inheritdoc
     */
    public function permissions(): MorphToMany
    {
        return $this->parentPermissions()->withTimestamps();
    }
}
