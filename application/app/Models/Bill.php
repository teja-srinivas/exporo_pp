<?php

namespace App\Models;

use Carbon\Carbon;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * Holds many commissions that a user should get their money for.
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon $pdf_created_at
 * @property Carbon $mail_sent_at
 * @property float $net (Dynamic column for joins)
 * @property float $gross (Dynamic column for joins)
 * @property User $user
 * @property Carbon|null $released_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Bill extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    protected $dates = [
        'released_at',
        'pdf_created_at',
        'mail_sent_at',
    ];

    protected $fillable = [
        'user_id',
        'released_at',
        'pdf_created_at'
    ];

    protected $casts = [
        // They're dynamic columns, but if they exist - cast them
        'net' => 'float',
        'gross' => 'float',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class, 'bill_id');
    }

    public function isReleased(): bool
    {
        return $this->released_at !== null && now()->greaterThanOrEqualTo($this->released_at);
    }

    /**
     * Calculates the aggregate gross value of all related commissions.
     *
     * @return float
     */
    public function getTotalGross(): float
    {
        return $this->commissions()->sum('gross');
    }

    /**
     * Calculates the aggregate net value of all related commissions.
     *
     * @return float
     */
    public function getTotalNet(): float
    {
        return $this->commissions()->sum('net');
    }

    public function scopeReleased(Builder $query, Carbon $now = null)
    {
        $query->whereNotNull('released_at')->where('released_at', '<=', $now ?? now());
    }

    public function scopeVisible(Builder $query)
    {
        $query->whereNotNull('pdf_created_at');
    }

    public function getBillingMonth(): Carbon
    {
        return $this->released_at->startOfMonth()->subMonth(1);
    }

    /**
     * Generates a human readable name for this bill, to be used for link texts.
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->getBillingMonth()->format('F Y');
    }

    /**
     * Returns a possible file name for this bill.
     *
     * To be used for user display and not for
     * the actual storage on a system disk.
     *
     * @return string
     */
    public function getFileName(): string
    {
        return implode('_', [
            'Exporo_Provisionsabrechnung_vom',
            ($this->created_at ?? now())->format('d.m.Y'),
            'für ',
            $this->user_id,
        ]) . '.pdf';
    }
}
