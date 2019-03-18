<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
 *
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

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
