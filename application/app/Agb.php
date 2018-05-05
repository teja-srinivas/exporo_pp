<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agb extends Model
{
    const DIRECTORY = 'agbs';

    static $_numberOfDefaults = null;

    protected $fillable = [
        'name', 'is_default'
    ];

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
     * Creates a human readable filename for this model
     * as the original filename is a random string.
     *
     * @return string
     */
    public function getReadableFilename(): string
    {
        return str_slug($this->name ?: env('APP_NAME', 'AGB'), '_', app()->getLocale()) . '.pdf';
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
        return self::$_numberOfDefaults ?: (self::$_numberOfDefaults = self::isDefault()->count());
    }
}
