<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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

    /**
     * @inheritdoc
     */
    public function users(): MorphToMany
    {
        return parent::users()->withTimestamps();
    }
}
