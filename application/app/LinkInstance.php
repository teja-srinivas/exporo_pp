<?php

namespace App;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Link;
use App\Helper\TagReplacer;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $link_id
 * @property int $user_id
 * @property string $url
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

        static::creating(static function (LinkInstance $instance) {
            $instance->hash = Str::random();
        });
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class);
    }

    public function getShortenedUrl(): string
    {
        $this->createIfNotExists();

        return "https://pp.exporo.link/{$this->hash}";
    }

    public function getRouteKeyName(): string
    {
        // Find instances by their hash for easier handling with routes
        return 'hash';
    }

    public function buildRealUrl(): string
    {
        $this->createIfNotExists();

        return TagReplacer::replace($this->link->url, [
            'reflink' => function(): string {
                return "?a_aid={$this->user->id}";
            },
        ]);
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml(): string
    {
        return $this->buildRealUrl();
    }

    protected function createIfNotExists()
    {
        if (! $this->exists) {
            $this->save();
        }
    }
}
