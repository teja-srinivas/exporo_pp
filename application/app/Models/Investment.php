<?php

namespace App\Models;

use Carbon\Carbon;
use OwenIt\Auditing\Auditable;
use App\Builders\InvestmentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @method static InvestmentBuilder query()
 *
 * @property int $id
 * @property int $bonus
 * @property float $amount
 * @property float $interest_rate
 * @property bool $is_first_investment
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
    use OptimusEncodedRouteKey;

    const MORPH_NAME = 'investment';

    const LEGACY_MORPH_NAME = 'legacy_investment';

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
        'acknowledged_at', 'rate', 'is_first_investment',
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
        return $this->acknowledged_at === null || $this->acknowledged_at >= now()->subWeeks(2);
    }

    public function isBillable(): bool
    {
        return ! $this->isRefundable() && $this->paid_at !== null;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
    }

    public function newEloquentBuilder($query): InvestmentBuilder
    {
        return new InvestmentBuilder($query);
    }
}
