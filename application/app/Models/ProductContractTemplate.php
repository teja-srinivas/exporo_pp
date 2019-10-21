<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Parental\HasParent;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property bool $vat_included
 * @property float $vat_amount
 *
 * @property Collection $bonuses
 */
class ProductContractTemplate extends ContractTemplate
{
    use HasParent;

    protected $casts = [
        'vat_included' => 'bool',
        'vat_amount' => 'float',
    ];

    protected $fillable = [
        'body', 'name', 'vat_included', 'vat_amount',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(ProductContract::class);
            // TODO ->where('type', );
    }

    public function bonuses(): BelongsToMany
    {
        return $this->belongsToMany(
            CommissionBonus::class,
            'contract_template_bonus',
            'template_id',
            'bonus_id'
        );
    }
}
