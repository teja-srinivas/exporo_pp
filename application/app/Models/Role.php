<?php

declare(strict_types=1);

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

    public const PARTNER = 'partner';

    public const INTERNAL = 'internal';

    public const ADMIN = 'admin';

    public const ROLES = [
        self::PARTNER,
        self::INTERNAL,
        self::ADMIN,
    ];

    public function permissions(): BelongsToMany
    {
        return parent::permissions()->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return parent::users()->withTimestamps();
    }

    public function canBeDeleted(): bool
    {
        return ! in_array($this->name, self::ROLES);
    }

    public function getDisplayName(): string
    {
        return ucfirst($this->name);
    }

    public function getColor(): string
    {
        switch ($this->name ?? '') {
            case self::ADMIN:
                return 'primary';
            case self::INTERNAL:
                return 'success';
            default:
                return 'light';
        }
    }
}
