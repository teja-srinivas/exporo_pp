<?php

namespace App;

use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    use OptimusEncodedRouteKey;

    public function projects()
    {
        return $this->hasMany(Project::class, 'schema_id');
    }
}
