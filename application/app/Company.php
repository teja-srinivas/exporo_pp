<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Company extends Model implements AuditableContract
{
    use Auditable;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
