<?php

namespace App\Models;

use Carbon\Carbon;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\JoinClause;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property Investment $investment
 * @property float $net
 * @property float $gross
 * @property float $bonus
 * @property string $note_private
 * @property string $note_public
 * @property User $user
 * @property UserDetails $userDetails
 * @property int $user_id
 * @property User $childUser
 * @property int $child_user_id
 * @property bool $on_hold
 * @property Investor $investor
 * @property Carbon $rejected_at
 * @property int $rejected_by
 * @property User $rejectedBy
 * @property Carbon $reviewed_at
 * @property int $reviewed_by
 * @property User $reviewedBy
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Commission extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    const TYPE_CORRECTION = 'correction';

    protected $casts = [
        'on_hold' => 'bool',
        'net' => 'float',
        'gross' => 'float',
        'bonus' => 'float',
        'note_private' => 'string',
        'note_public' => 'string',
    ];

    protected $dates = [
        'rejected_at',
        'reviewed_at',
    ];

    protected $fillable = [
        'bill_id', 'model_type', 'model_id', 'user_id', 'net', 'gross',
        'on_hold', 'note_public', 'note_private',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userDetails(): BelongsTo
    {
        return $this->belongsTo(UserDetails::class, 'user_id', 'id', 'user_details');
    }

    public function childUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'child_user_id');
    }

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Helper function to quickly get the investment relationship.
     *
     * This exists because we still cannot check a whereHas on a morphTo
     * (see https://github.com/laravel/framework/issues/5429).
     *
     * @return BelongsTo
     */
    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class, 'model_id');
    }

    /**
     * Helper function to quickly get the investor relationship.
     *
     * This exists because we still cannot check a whereHas on a morphTo
     * (see https://github.com/laravel/framework/issues/5429).
     *
     * @return BelongsTo
     */
    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class, 'model_id');
    }

    public function isAcceptable()
    {
        return $this->investment->isBillable();
    }

    /**
     * Checks if the given model being referenced is actually billable.
     * e.g. Investments not being processed yet.
     *
     * @param Builder $query
     */
    public function scopeIsAcceptable(Builder $query)
    {
        // Investments
        $investment = new Investment();
        $query->leftJoin($investment->getTable(), function (JoinClause $join) use ($investment) {
            $join->where('commissions.model_type', '=', 'investment');
            $join->on('commissions.model_id', $investment->getTable() . '.id');

            $investment->scopeBillable($join);
        });

        // Investors
        $investor = new Investor();
        $query->leftJoin($investor->getTable(), function (JoinClause $join) use ($investor) {
            $join->where('commissions.model_type', '=', 'investor');
            $join->on('commissions.model_id', $investor->getTable() . '.id');
        });
    }

    public function isBillable(): bool
    {
        return !$this->on_hold && $this->rejected_at === null && $this->isAcceptable();
    }

    public function scopeIsBillable(Builder $query)
    {
        $query->whereNotNull('reviewed_at')
            ->whereNull('bill_id');
    }

    /**
     * Checks whether the commission is not already included in a bill
     * and has not yet been rejected.
     *
     * @param Builder $query
     */
    public function scopeIsOpen(Builder $query)
    {
        $query->whereNull('bill_id');
        $query->where(function (Builder $query) {
            $query->whereNull('commissions.rejected_at');
            $query->orWhere('commissions.rejected_at', '>=', now()->subMonth());
        });
    }

    public function scopeForUser(Builder $query, $user)
    {
        $query->where($this->getTable() . '.user_id', is_numeric($user) ? $user : $user->id);
    }

    /**
     * Only queries the commissions that we actually want to process
     * after our "launch date".
     *
     * @param Builder $query
     */
    public function scopeAfterLaunch(Builder $query)
    {
        $query->where(function (Builder $query) {
            $startDate = Carbon::createFromDate(2018, 11, 1);

            $query->where(function (Builder $query) use ($startDate) {
                $query->where('model_type', Investment::MORPH_NAME);
                $query->whereDate('investments.created_at', '>=', $startDate);
            });

            $query->orWhere(function (Builder $query) use ($startDate) {
                $query->where('model_type', Investor::MORPH_NAME);
                $query->whereDate('investors.activation_at', '>=', $startDate);
            });
        });
    }

    public function reject(?User $user)
    {
        if ($user === null) {
            $this->rejected_by = null;
            $this->rejected_at = null;
            return;
        }

        $this->rejected_by = $user->id;
        $this->rejected_at = now();
    }

    public function review(?User $user)
    {
        if ($user === null) {
            $this->reviewed_by = null;
            $this->reviewed_at = null;
            return;
        }

        $this->reviewed_by = $user->id;
        $this->reviewed_at = now();
    }
}
