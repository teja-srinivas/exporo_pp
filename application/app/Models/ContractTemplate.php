<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $body
 * @property int $cancellation_days
 * @property int $claim_years
 * @property bool $vat_included
 * @property float $vat_amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read bool $is_default
 *
 * @property Company $company
 * @property Collection $bonuses
 * @property Collection $contracts
 */
class ContractTemplate extends Model
{
    protected $casts = [
        'cancellation_days' => 'int',
        'claim_years' => 'int',
        'vat_included' => 'bool',
        'vat_amount' => 'float',
    ];

    protected $fillable = [
        'body', 'name', 'cancellation_days', 'claim_years', 'vat_included', 'vat_amount',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
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

    public function getIsDefaultAttribute()
    {
        return $this->company->default_contract_template_id === $this->getKey();
    }

    public function makeDefault()
    {
        $this->company->contractTemplate()->associate($this);
        $this->company->save();
    }
}
