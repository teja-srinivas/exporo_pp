<?php

namespace App\Models;

use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Company extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
