<?php

declare(strict_types=1);

namespace App\Models;

use App\Helper\Rules;
use Parental\HasParent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property bool $vat_included
 * @property float $vat_amount
 *
 * @property Collection $bonuses
 */
class ProductContract extends Contract
{
    use HasParent;

    public const STI_TYPE = 'product';

    public static function boot()
    {
        parent::boot();

        self::deleted(static function (ProductContract $contract) {
            $contract->bonuses()->delete();
        });
    }

    public function bonuses(): HasMany
    {
        return $this->hasMany(CommissionBonus::class);
    }

    public function hasOverhead(): bool
    {
        return $this->bonuses()->where('is_overhead', true)->exists();
    }

    public function getValidationRules(): array
    {
        return parent::getValidationRules() + Rules::byPermission([
            'management.contracts.update-vat-details' => [
                'vat_amount' => ['numeric'],
                'vat_included' => ['boolean'],
            ],
        ]);
    }
}
