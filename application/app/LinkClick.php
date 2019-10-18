<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $instance_id
 * @property string $device
 * @property string $country
 * @property Carbon $created_at
 *
 * @property-read LinkInstance $link
 */
class LinkClick extends Model
{
    // Disable the updated_at column
    public const UPDATED_AT = null;

    protected $fillable = [
        'device', 'country',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(LinkInstance::class, 'instance_id');
    }
}
