<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Extension to the spatie permission model to add timestamp support
 * to its roles relationship.
 *
 * @package App
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    /**
     * @inheritdoc
     */
    public function roles(): BelongsToMany
    {
        return parent::roles()->withTimestamps();
    }
}
