<?php

namespace App;

use App\Traits\Importable;
use Carbon\Carbon;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $bonus
 * @property float $interest_rate
 * @property boolean $is_first_investment
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
        'paid_at', 'id', 'investsum', 'updated_at', 'created_at', 'investor_id', 'project_id', 'description'
    ];

    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class, 'investor_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function commissions(): MorphOne
    {
        return $this->morphOne(Commission::class, 'model');
    }

    public function isRefundable(): bool
    {
        return $this->acknowledged_at < now()->subWeeks(2);
    }

    public function isBillable(): bool
    {
        return !$this->isRefundable() && $this->paid_at !== null;
    }

    public function scopeRefundable(Builder $query)
    {
        $query->where('acknowledged_at', '<=', now()->subWeeks(2));
    }

    public function scopeBillable(Builder $query)
    {
        $query->refundable()->whereNotNull('paid_at');
    }
}
