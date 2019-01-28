<?php

namespace App\Models;

use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Extension to the spatie role model to add timestamp support
 * to its permissions relationship.
 *
 * @property int $id
 * @property string $name
 */
class Role extends \Spatie\Permission\Models\Role
{
    use OptimusEncodedRouteKey;

    const PARTNER = 'partner';
    const LOCKED = 'gesperrt';
    const INTERNAL = 'internal';
    const ADMIN = 'admin';

    const ROLES = [
        self::PARTNER,
        self::INTERNAL,
        self::ADMIN,
    ];

    /**
     * @inheritdoc
     */
    public function permissions(): BelongsToMany
    {
        return parent::permissions()->withTimestamps();
    }

    public function canBeDeleted(): bool
    {
        return !in_array($this->name, self::ROLES);
    }

    public function getDisplayName()
    {
        return ucfirst($this->name);
    }

    public function getColor()
    {
        switch ($this->name ?? '') {
            case Role::ADMIN:
                return 'primary';
            case Role::INTERNAL:
                return 'success';
            default:
                return 'light';
        }
    }
}
