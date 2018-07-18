<?php

namespace App;

use App\Traits\Encryptable;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property User $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Investor extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;
    use Encryptable;

    const MORPH_NAME = 'investor';

    public $incrementing = false;

    protected $fillable = [
        'id', 'first_name', 'last_name', 'created_at', 'updated_at'
    ];

    protected $encryptable = [
        'first_name',
        'last_name'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(Project::class, Investment::class);
    }

    public function commissions(): MorphOne
    {
        return $this->morphOne(Commission::class, 'model');
    }

    public static function getNewestUpdatedAtDate()
    {
        return DB::table('investors')
            ->orderBy('updated_at', 'desc')
            ->get('updated_at');
    }
}
