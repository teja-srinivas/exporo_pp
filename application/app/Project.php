<?php

namespace App;

use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use OptimusEncodedRouteKey;

    public $incrementing = false;


    public function schema(): BelongsTo
    {
        return $this->belongsTo(Schema::class, 'schema_id');
    }
}
