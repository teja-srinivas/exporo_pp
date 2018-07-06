<?php

namespace App;

use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property Investment $investment
 * @property bool $on_hold
 */
class Commission extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    protected $casts = [
        'on_hold' => 'bool',
    ];


    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function model(): MorphTo
    {
        return $this->morphTo('model');
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

    public function isBillable(): bool
    {
        return !$this->on_hold && $this->investment->isBillable();
    }

    public function scopeIsBillable(Builder $query)
    {
        // We can delay commissions for later bills
        $query->where('on_hold', '!=', true)->whereHas('investment', function (Builder $query) {
            $query->billable();
        });
    }
}
