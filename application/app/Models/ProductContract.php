<?php

declare(strict_types=1);

namespace App\Models;

use Parental\HasParent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection $bonuses
 */
class ProductContract extends Contract
{
    use HasParent;

    public const STI_TYPE = 'product';

    public function bonuses(): HasMany
    {
        return $this->hasMany(CommissionBonus::class);
    }

    public function hasOverhead(): bool
    {
        return $this->bonuses()->where('is_overhead', true)->exists();
    }
}
