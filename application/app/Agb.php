<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Agb extends Model
{
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
        return $query->where('is_default', true)->latest()->first();
    }
}
