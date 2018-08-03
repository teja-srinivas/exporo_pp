<?php

namespace App;

use App\Traits\Importable;
use Carbon\Carbon;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Schema $schema;
 * @property Carbon $payback_min_at
 * @property Carbon $launched_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Project extends Model
{
    use Importable;
    use OptimusEncodedRouteKey;

    public $incrementing = false;

    protected $dates = [
        'launched_at',
        'payback_min_at',
    ];

    protected $fillable = [
        'id', 'name', 'type', 'created_at', 'updated_at', 'launched_at',
        'payback_min_at', 'approved_at', 'approved_by', 'schema_id', 'capital_cost',
        'interest_rate', 'runtime'
    ];

    public function schema(): BelongsTo
    {
        return $this->belongsTo(Schema::class, 'schema_id', 'id');
    }

    public function investments(): hasMany
    {
        return $this->hasMany(Investment::class, 'project_id', 'id');
    }

    public function runtimeInMonths(): int
    {
        if($this->runtime){
            return $this->runtime;
        }
        return $this->payback_min_at->diffInMonths($this->launched_at);
    }
}
