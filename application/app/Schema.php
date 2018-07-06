<?php

namespace App;

use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Schema extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    public function projects()
    {
        return $this->hasMany(Project::class, 'schema_id');
    }
}
