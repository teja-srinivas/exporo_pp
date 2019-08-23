<?php

namespace App\Models;

use Carbon\Carbon;
use App\Builders\ContractBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;

/**
 * @method static ContractBuilder query()
 *
 * @property int $id
 * @property int $user_id
 * @property int $template_id
 * @property int $cancellation_days
 * @property int $claim_years The number of years since accepted_at, we can generate commissions for
 * @property bool $vat_included
 * @property float $vat_amount
 * @property string $special_agreement
 * @property Carbon $accepted_at The date the user fully accepted the contract.
 * @property Carbon $released_at The date we confirmed the contract, but has not yet been accepted by the user.
 * @property Carbon $terminated_at The date the contract has been terminated (either by hand or automation).
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ContractTemplate $template
 * @property Collection $bonuses
 * @property User $user
 */
class Contract extends Model
{
    use OptimusEncodedRouteKey;

    protected $casts = [
        'cancellation_days' => 'int',
        'claim_years' => 'int',
        'vat_included' => 'bool',
        'vat_amount' => 'float',
    ];

    protected $dates = [
        'accepted_at',
        'released_at',
        'terminated_at',
    ];

    protected $fillable = [
        'cancellation_days',
        'claim_years',
        'special_agreement',
        'vat_included',
        'vat_amount',
        'released_at',
    ];

    public function bonuses(): HasMany
    {
        return $this->hasMany(CommissionBonus::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContractTemplate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasOverhead(): bool
    {
        return $this->bonuses()->where('is_overhead', true)->exists();
    }

    public function isActive(): bool
    {
        return $this->terminated_at === null;
    }

    public function isEditable(): bool
    {
        return $this->accepted_at === null;
    }

    public function newEloquentBuilder($query): ContractBuilder
    {
        return new ContractBuilder($query);
    }

    public static function fromTemplate(ContractTemplate $template): self
    {
        $contract = new self();
        $contract->forceFill([
            'template_id' => $template->getKey(),
            'vat_amount' => $template->vat_amount,
            'vat_included' => $template->vat_included,
            'cancellation_days' => $template->cancellation_days,
            'claim_years' => $template->claim_years,
        ]);

        return $contract;
    }
}
