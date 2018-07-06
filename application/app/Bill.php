<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Holds many commissions that a user should get their money for.
 *
 * @property int $id
 * @property int $user_id
 * @property User $user
 * @property Carbon|null $released_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Bill extends Model
{
    protected $dates = [
        'released_at',
    ];

    protected $fillable = [
        'released_at',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class, 'bill_id');
    }

    public function isReleased(): bool
    {
        return $this->released_at !== null && now()->greaterThanOrEqualTo($this->released_at);
    }

    /**
     * Calculates the aggregate gross value of all related commissions.
     *
     * @return float
     */
    public function getTotalGross(): float
    {
        return $this->commissions()->sum('gross');
    }

    /**
     * Calculates the aggregate net value of all related commissions.
     *
     * @return float
     */
    public function getTotalNet(): float
    {
        return $this->commissions()->sum('net');
    }

    public function scopeReleased(Builder $query)
    {
        $query->whereNotNull('released_at')->where('released_at', '<=', now());
    }
}
