<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Parental\HasParent;
use Parental\HasChildren;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
