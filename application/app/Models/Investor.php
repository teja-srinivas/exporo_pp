<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Person;
use App\Traits\Encryptable;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable;
use App\Builders\InvestorBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @method static InvestorBuilder query()
 *
 * @property int $id
 * @property User $user
 * @property int $user_id
 * @property UserDetails $details
 * @property string $first_name
 * @property string $last_name
 * @property string $email
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
    use SoftDeletes;

    public const MORPH_NAME = 'investor';

    public $incrementing = false;

    protected $fillable = [
        'id', 'first_name', 'last_name', 'created_at', 'updated_at', 'user_id', 'claim_end', 'activation_at',
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
        'last_name',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'affiliated_partner_ref_link_id', 'id');
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

    public function newEloquentBuilder($query): InvestorBuilder
    {
        return new InvestorBuilder($query);
    }
}
