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
    use SpatieRoles;

    /**
     * @inheritdoc
     */
    public function roles(): MorphToMany
    {
        return SpatieRoles::roles()->withTimestamps();
    }

    /**
     * @inheritdoc
     */
    public function permissions(): MorphToMany
    {
        return SpatieRoles::permissions()->withTimestamps();
    }
}
