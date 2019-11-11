<?php

declare(strict_types=1);

namespace App\Models;

use Parental\HasParent;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'body', 'name', 'vat_included', 'vat_amount', 'is_default',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(ProductContract::class);
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

    public function createInstance(User $user): ProductContract
    {
        $contract = new ProductContract([
            'vat_included' => $this->vat_included,
            'vat_amount' => $this->vat_amount,
        ]);

        $contract->template()->associate($this);
        $contract->user()->associate($user);

        $contract->save();

        $contract->bonuses()->saveMany($this->bonuses->map(
            static function (CommissionBonus $bonus) {
                return $bonus->replicate();
            }
        ));

        return $contract;
    }
}
