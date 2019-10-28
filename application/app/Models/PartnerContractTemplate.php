<?php

declare(strict_types=1);

namespace App\Models;

use Parental\HasParent;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $cancellation_days
 * @property int $claim_years
 */
class PartnerContractTemplate extends ContractTemplate
{
    use HasParent;

    protected $casts = [
        'cancellation_days' => 'int',
        'claim_years' => 'int',
    ];

    protected $fillable = [
        'body', 'name', 'cancellation_days', 'claim_years',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(PartnerContract::class);
    }
}
