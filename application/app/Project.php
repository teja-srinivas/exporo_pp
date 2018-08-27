<?php

namespace App;

use App\Traits\Importable;
use Carbon\Carbon;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $runtime
 * @property User $approved
 * @property Schema $schema;
 * @property Carbon $payback_min_at
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
        'payback_min_at', 'approved_at', 'approved_by', 'schema_id', 'capital_cost',
        'interest_rate', 'runtime', 'provision_type'
    ];

    protected $casts = [
        'provision_type' => 'int',
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

    public function wasApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function runtimeInMonths(): int
    {
        return $this->runtime ?? $this->payback_min_at->diffInMonths($this->launched_at);
    }
}
