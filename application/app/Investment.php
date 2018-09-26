<?php

namespace App;

use App\Traits\Importable;
use Carbon\Carbon;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $id
 * @property int $bonus
 * @property float $amount
 * @property float $interest_rate
 * @property boolean $is_first_investment
 * @property Project $project
 * @property Investor $investor
 * @property Collection $commissions
 * @property Carbon $acknowledged_at
 * @property Carbon $cancelled_at
 * @property Carbon $paid_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Investment extends Model implements AuditableContract
{
    use Auditable;
    use Importable;
    use OptimusEncodedRouteKey;

    const MORPH_NAME = 'investment';

    public $incrementing = false;

    protected $casts = [
        'is_first_investment' => 'bool',
        'interest_rate' => 'float',
        'bonus' => 'int',
    ];

    protected $dates = [
        'acknowledged_at',
        'cancelled_at',
        'paid_at',
    ];

    protected $fillable = [
        'paid_at', 'id', 'amount', 'updated_at', 'created_at',
        'investor_id', 'project_id', 'interest_rate', 'paid_at',
        'acknowledged_at', 'rate', 'is_first_investment'
    ];

    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class, 'investor_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function commissions(): MorphMany
    {
        return $this->morphMany(Commission::class, 'model');
    }

    public function isRefundable(): bool
    {
        return $this->acknowledged_at >= now()->subWeeks(2) || $this->acknowledged_at === null;
    }

    public function isBillable(): bool
    {
        return !$this->isRefundable() && $this->paid_at !== null;
    }

    /**
     * @param Builder|\Illuminate\Database\Query\Builder $query
     */
    public function scopeRefundable($query)
    {
        $query->where('acknowledged_at', '<=', now()->subWeeks(2));
    }

    /**
     * @param Builder|\Illuminate\Database\Query\Builder $query
     */
    public function scopeBillable($query)
    {
        $this->scopeRefundable($query);
        $query->whereNotNull('paid_at');
    }
}
