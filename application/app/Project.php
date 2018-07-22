<?php

namespace App;

use App\Traits\Importable;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use Importable;
    use OptimusEncodedRouteKey;

    public $incrementing = false;

    protected $fillable = [
        'id', 'name', 'type', 'created_at', 'updated_at'
    ];

    public function schema(): BelongsTo
    {
        return $this->belongsTo(Schema::class, 'schema_id', 'id');
    }

    public function investments(): hasMany
    {
        return $this->hasMany(Investment::class, 'project_id', 'id');
    }
}
