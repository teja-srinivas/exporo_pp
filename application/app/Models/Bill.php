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
    ];

    protected $fillable = [
        'user_id',
        'released_at',
        'pdf_created_at'
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

    /**
     * Generates a human readable name for this bill, to be used for link texts.
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        // TODO is there a better way to decide on a name for a bill?
        return $this->created_at->startOfMonth()->subMonth(1)->format('F Y');
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
            $this->id,
            $this->created_at->format('d.m.Y'),
            $this->user_id,
        ]) . '.pdf';
    }

    /**
     * Returns a simplified bill model that contains
     * - the id,
     * - the total sum of the bill
     * - the amount of commissions contained in the bill
     *
     * Optionally, a specific user can be provided.
     *
     * @param int|null $forUser The user ID
     * @return Builder
     */
    public static function getDetailsPerUser(?int $forUser = null): Builder
    {
        return self::query()
            ->join('commissions', 'commissions.bill_id', 'bills.id')
            ->groupBy('bills.id')
            ->select('bills.id', 'bills.user_id', 'bills.created_at')
            ->selectRaw('COUNT(commissions.id) as commissions')
            ->selectRaw('SUM(commissions.gross) as gross')
            ->selectRaw('SUM(commissions.net) as net')
            ->when($forUser !== null, function (Builder $query) use ($forUser) {
                $query->where('bills.user_id', $forUser);
            });
    }
}
