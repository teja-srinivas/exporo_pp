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
 * @property int $claim_years
 * @property bool $vat_included
 * @property float $vat_amount
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
