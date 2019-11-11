<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use App\Events\ProjectUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $runtime
 * @property int $commission_type
 * @property float $margin
 * @property User $approved
 * @property string|null $location
 * @property string|null $rating
 * @property string|null $type
 * @property string|null $status
 * @property float|null $coupon_rate
 * @property float|null $funding_target
 * @property string|null $intermediator
 * @property Schema $schema;
 * @property CommissionType $commissionType;
 * @property Carbon $payback_min_at
 * @property Carbon $payback_max_at
 * @property Carbon $launched_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $approved_at
 *
 * @property-read Collection $investments
 * @property-read int $investments_count
 */
class Project extends Model
{
    use OptimusEncodedRouteKey;

    public const STATUS_COMING_SOON = 'coming_soon';
    public const STATUS_IN_FUNDING = 'in_funding';

    public const STATUSES = [
        self::STATUS_COMING_SOON,
        self::STATUS_IN_FUNDING,
    ];

    public $incrementing = false;

    protected $dates = [
        'launched_at',
        'payback_min_at',
        'payback_max_at',
    ];

    protected $fillable = [
        'id', 'name', 'created_at', 'updated_at', 'launched_at',
        'payback_min_at', 'payback_max_at', 'approved_at', 'approved_by', 'schema_id', 'capital_cost',
        'interest_rate', 'runtime', 'commission_type',
    ];

    protected $casts = [
        'commission_type' => 'int',
        'interest_rate' => 'float',
        'margin' => 'float',
        'coupon_rate' => 'float',
        'funding_target' => 'float',
    ];

    protected $dispatchesEvents = [
        'updated' => ProjectUpdated::class,
    ];

    public function schema(): BelongsTo
    {
        return $this->belongsTo(Schema::class, 'schema_id', 'id');
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class, 'project_id', 'id');
    }

    public function approved(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function commissionType()
    {
        return $this->belongsTo(CommissionType::class, 'commission_type', 'id');
    }

    public function wasApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function runtimeInMonths(): int
    {
        return $this->diffInMonths($this->launched_at, $this->payback_max_at);
    }

    public function runtimeFactor(): float
    {
        $runtime = $this->runtimeInMonths();

        return min(round($runtime / 24, 2), 1);
    }

    public function marginPercentage(): float
    {
        return (float) ($this->margin / 100);
    }

    protected function diffInMonths(?DateTime $date1, ?DateTime $date2)
    {
        if ($date1 === null || $date2 === null) {
            return 0;
        }

        $diff = $date1->diff($date2);

        $months = $diff->y * 12 + $diff->m + $diff->d / 30;

        return (int) round($months);
    }
}
