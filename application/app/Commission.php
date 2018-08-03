<?php

namespace App;

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
 * @property User $user
 * @property bool $on_hold
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

    protected $casts = [
        'on_hold' => 'bool',
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
        return $this->belongsTo(Investment::class, 'model_id')
                    ->where('commissions.model_type', Investment::MORPH_NAME);
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
        return $this->belongsTo(Investor::class, 'model_id')
                    ->where('commissions.model_type', Investor::MORPH_NAME);
    }

    public function isAcceptable()
    {
        return $this->investment->isBillable();
    }

    public function scopeIsAcceptable(Builder $query)
    {
        // TODO add support for investors (aka newly acquired users)

        // It's faster to do a manual join than using whereHas
        // https://github.com/laravel/framework/issues/18415
        $query->join('investments', function (JoinClause $join) {
            $join->where('commissions.model_type', 'investment');
            $join->on('commissions.model_id', 'investments.id');
            (new Investment)->scopeBillable($join);
        });
    }

    public function isBillable(): bool
    {
        return !$this->on_hold && $this->rejected_at === null && $this->isAcceptable();
    }

    public function scopeIsBillable(Builder $query)
    {
        // We can delay commissions for later bills
        $query->where('on_hold', '!=', true)
            ->whereNull('commissions.rejected_at')
            ->whereNull('bill_id')
            ->isAcceptable();
    }

    public function scopeIsOpen(Builder $query)
    {
        $query->whereNull('bill_id');
        $query->where(function (Builder $query) {
            $query->whereNull('commissions.rejected_at');
            $query->orWhere('commissions.rejected_at', '>=', now()->subMonth());
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
