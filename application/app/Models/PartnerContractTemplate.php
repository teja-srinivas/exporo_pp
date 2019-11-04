<?php

declare(strict_types=1);

namespace App\Models;

use Parental\HasParent;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $cancellation_days
 * @property int $claim_years
 * @property bool $is_exclusive
 * @property bool $allow_overhead
 */
class PartnerContractTemplate extends ContractTemplate
{
    use HasParent;

    protected $casts = [
        'cancellation_days' => 'int',
        'claim_years' => 'int',
        'is_exclusive' => 'bool',
        'allow_overhead' => 'bool',
    ];

    protected $fillable = [
        'body', 'name', 'cancellation_days', 'claim_years', 'is_default',
        'is_exclusive', 'allow_overhead',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(PartnerContract::class);
    }

    public function createInstance(User $user): PartnerContract
    {
        $contract = new PartnerContract([
            'cancellation_days' => $this->cancellation_days,
            'claim_years' => $this->claim_years,
            'is_exclusive' => $this->is_exclusive,
            'allow_overhead' => $this->allow_overhead,
        ]);

        $contract->template()->associate($this);
        $contract->user()->associate($user);

        $contract->save();

        return $contract;
    }
}
