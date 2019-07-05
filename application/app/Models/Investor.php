<?php

namespace App\Models;

use App\Traits\Encryptable;
use App\Traits\Person;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $id
 * @property User $user
 * @property int $user_id
 * @property UserDetails $details
 * @property string $first_name
 * @property string $last_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $activation_at
 * @property Carbon $claim_end
 */
class Investor extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;
    use Encryptable;
    use Person;

    const MORPH_NAME = 'investor';

    public $incrementing = false;

    protected $fillable = [
        'id', 'first_name', 'last_name', 'created_at', 'updated_at', 'user_id', 'claim_end', 'activation_at'
    ];

    protected $dates = [
        'activation_at',
        'claim_end',
    ];

    protected $casts = [
        'vat_included' => 'bool',
    ];

    protected $encryptable = [
        'first_name',
        'last_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function details()
    {
        return $this->belongsTo(UserDetails::class, 'user_id', 'id');
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
}
