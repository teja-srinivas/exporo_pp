<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use App\Models\Link;
use App\Models\User;
use App\Models\BannerLink;
use App\Helper\TagReplacer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use App\Services\DeviceIdentification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $link_type
 * @property int $link_id
 * @property int $user_id
 * @property string $hash
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Link $link
 * @property-read User $user
 */
class LinkInstance extends Model implements Htmlable
{
    protected $with = ['link'];

    protected $fillable = [
        'user_id',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (self $instance) {
            $instance->hash = Str::random();
        });
    }

    public function link(): MorphTo
    {
        return $this->morphTo('link');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class, 'instance_id');
    }

    public function getShortenedUrl(): string
    {
        $this->createIfNotExists();

        return URL::route('short-link', $this->hash);
    }

    public function getRouteKeyName(): string
    {
        // Find instances by their hash for easier handling with routes
        return 'hash';
    }

    public function buildRealUrl(): string
    {
        $this->createIfNotExists();

        return TagReplacer::addLinkId(
            TagReplacer::replace($this->link->url, TagReplacer::getUserTags($this->user)),
            $this->link->id,
            $this->user,
            $this->link_type
        );
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml(): string
    {
        if (Gate::allows($this->getPermission())) {
            return $this->getShortenedUrl();
        }

        return $this->buildRealUrl();
    }

    protected function getPermission(): string
    {
        static $prefix = 'features.link-shortener';
        static $map = [
            Link::MORPH_NAME => 'links',
            BannerLink::MORPH_NAME => 'banners',
        ];

        return "{$prefix}.{$map[$this->link_type]}";
    }

    protected function createIfNotExists()
    {
        if ($this->exists) {
            return;
        }

        $this->save();
    }

    public function createClick(Request $request): LinkClick
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */

        return $this->clicks()->create([
            'device' => DeviceIdentification::identify(),
            'country' => null, // TODO
        ]);
    }
}
