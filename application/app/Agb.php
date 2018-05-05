<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agb extends Model
{
    static $_numberOfDefaults = null;

    /**
     * Returns all the users that have signed this instance of the AGB.
     *
     *  @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Fetches the currently active Agb.
     *
     * @param $query
     * @return mixed
     */
    public function scopeCurrent($query)
    {
        return $query->isDefault()->latest()->first();
    }

    public function scopeIsDefault($query, bool $value = true)
    {
        $query->where('is_default', $value);
    }

    /**
     * Indicates if this model can be deleted.
     *
     * It cannot be deleted if:
     * - Users are depending on it
     * - It's marked as default and there is no other fallback
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return $this->users->count() === 0 && (!$this->is_default || $this->numberOfAvailableDefaults() > 1);
    }

    protected function numberOfAvailableDefaults()
    {
        return self::$_numberOfDefaults ?? (self::$_numberOfDefaults = self::isDefault()->count());
    }
}
