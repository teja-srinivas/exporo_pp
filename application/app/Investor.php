<?php

namespace App;

use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Investor extends Model
{
    use OptimusEncodedRouteKey;

    const MORPH_NAME = 'investor';

    public $incrementing = false;

    protected $fillable = [
        'first_name', 'last_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'last_user_id');
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(Project::class, Investment::class);
    }

    public function commissions()
    {
        return $this->morphOne(Commission::class, 'model');
    }
}
