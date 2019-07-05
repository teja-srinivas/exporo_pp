<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $template_id
 * @property int $cancellation_days
 * @property int $claim_years The number of years since accepted_at, we can generate commissions for
 * @property bool $vat_included
 * @property float $vat_amount
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
        'vat_included',
        'vat_amount',
    ];

    public function bonuses()
    {
        return $this->hasMany(CommissionBonus::class);
    }

    public function template()
    {
        return $this->belongsTo(ContractTemplate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasOverhead()
    {
        return $this->bonuses()->where('is_overhead', true)->exists();
    }

    public function isActive()
    {
        return $this->terminated_at === null;
    }

    public static function fromTemplate(ContractTemplate $template): self
    {
        return new Contract([
            'template_id' => $template->getKey(),
            'vat_amount' => $template->vat_amount,
            'vat_included' => $template->vat_included,
            'cancellation_days' => $template->cancellation_days,
            'claim_years' => $template->claim_years,
        ]);
    }
}
