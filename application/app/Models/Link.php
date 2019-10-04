<?php

namespace App\Models;

use App\LinkClick;
use Carbon\Carbon;
use App\LinkInstance;
use InvalidArgumentException;
use App\Builders\LinkBuilder;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @method static LinkBuilder query()
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read LinkInstance $userInstance
 * @property-read Collection $users
 * @property-read Collection $instances
 * @property-read Collection $clicks
 */
class Link extends Model implements AuditableContract
{
    use Auditable;

    public const MORPH_NAME = 'link';

    protected $fillable = [
        'title', 'description', 'url',
    ];

    /**
     * Returns a list of link instances that redirect to the proper URL.
     *
     * @return MorphMany
     */
    public function instances(): MorphMany
    {
        return $this->morphMany(LinkInstance::class, 'link');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Returns or creates a new link instance for the currently authorized user.
     *
     * @return MorphOne
     */
    public function userInstance(): MorphOne
    {
        $user = auth()->user();

        if ($user === null) {
            throw new InvalidArgumentException('Cannot grab link instance during unauthorized access');
        }

        return $this->morphOne(LinkInstance::class, 'link')
            ->where('user_id', $user->getAuthIdentifier())
            ->withDefault(static function (LinkInstance $instance) use ($user) {
                $instance->user()->associate($user);
            });
    }

    /**
     * Returns a list of clicks that are associated with this link via the instances.
     *
     * @return HasManyThrough
     */
    public function clicks(): HasManyThrough
    {
        return $this->hasManyThrough(LinkClick::class, LinkInstance::class);
    }

    public function newEloquentBuilder($query): LinkBuilder
    {
        return new LinkBuilder($query);
    }
}
