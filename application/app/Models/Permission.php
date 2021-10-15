<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * @property string $name
 * @property string[] $protected
 */
class Permission extends \Spatie\Permission\Models\Permission
{
    protected $casts = [
        'protected' => 'json',
    ];

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        // Add timestamp support
        return parent::roles()->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        // Add timestamp support
        return parent::users()->withTimestamps();
    }

    /**
     * Checks if the current permission is protected from being
     * added/used by the given role(s). This is to make sure
     * that we don't give users permission to areas they
     * definitely should not have access to.
     *
     * @param string|string[]|Role|Role[]|EloquentCollection $roles
     * @return bool
     */
    public function isProtected($roles): bool
    {
        if ($this->protected === null) {
            return false;
        }

        if ($roles instanceof EloquentCollection) {
            $roles = $roles->pluck('name');
        }

        if ($roles instanceof Model) {
            $roles = [$roles->name];
        }

        return collect($roles)->intersect($this->protected)->isNotEmpty();
    }
}
