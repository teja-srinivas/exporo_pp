<?php

namespace App;

use App\Models\Link;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
class LinkInstance extends Model
{
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

    public function getRouteKeyName(): string
    {
        // Find instances by their hash for easier handling with routes
        return 'hash';
    }
}
