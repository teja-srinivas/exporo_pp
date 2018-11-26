<?php

namespace App\Models;

use App\Traits\Importable;
use Carbon\Carbon;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $description
 * @property int $runtime
 * @property int $commission_type
 * @property User $approved
 * @property Schema $schema;
 * @property Carbon $payback_min_at
 * @property Carbon $payback_max_at
 * @property Carbon $launched_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $approved_at
 */
class Project extends Model
{
    use Importable;
    use OptimusEncodedRouteKey;

    public $incrementing = false;

    protected $dates = [
        'launched_at',
        'payback_min_at',
        'payback_max_at',
    ];

    protected $fillable = [
        'id', 'name', 'type', 'created_at', 'updated_at', 'launched_at',
        'payback_min_at', 'payback_max_at', 'approved_at', 'approved_by', 'schema_id', 'capital_cost',
        'interest_rate', 'runtime', 'commission_type'
    ];

    protected $casts = [
        'commission_type' => 'int',
        'interest_rate' => 'float',
        'margin' => 'float',
    ];


    public function schema(): BelongsTo
    {
        return $this->belongsTo(Schema::class, 'schema_id', 'id');
    }

    public function investments(): hasMany
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
        return $this->runtime ?? $this->diffInMonths($this->launched_at, $this->payback_max_at);
    }

    public function runtimeFactor(): float
    {
        $runtime = $this->runtimeInMonths();

        return $runtime / 24 < 1 ? round($runtime / 24, 2) : 1;
    }

    public function marginPercentage(): float
    {
        return (float)($this->margin / 100);
    }

    protected function diffInMonths(\DateTime $date1, \DateTime $date2)
    {
        $diff =  $date1->diff($date2);

        $months = $diff->y * 12 + $diff->m + $diff->d / 30;

        return (int) round($months);
    }
}
