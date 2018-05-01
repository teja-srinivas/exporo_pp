<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Extension to the spatie role model to add timestamp support
 * to its permissions relationship.
 *
 * @package App
 */
class Role extends \Spatie\Permission\Models\Role
{
    /**
     * @inheritdoc
     */
    public function permissions(): BelongsToMany
    {
        return parent::permissions()->withTimestamps();
    }
}
