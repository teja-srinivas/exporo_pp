<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agb extends Model
{
    /**
     * Returns all the users that have signed this instance of the AGB.
     *
     *  @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
