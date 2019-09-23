<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Builders\AgbBuilder;
use OwenIt\Auditing\Auditable;
use App\Interfaces\FileReference;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @method static AgbBuilder query()
 *
 * @property string $type
 * @property string $name
 * @property string $filename
 * @property boolean $is_default
 * @property Carbon|null $effective_from
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Agb extends Model implements AuditableContract, FileReference
{
    use Auditable;
    use OptimusEncodedRouteKey;

    const DIRECTORY = 'agbs';

    const TYPE_AG = 'ag';

    const TYPE_GMBH = 'gmbh';

    const TYPES = [self::TYPE_AG, self::TYPE_GMBH];

    protected $casts = [
        'is_default' => 'bool',
    ];

    protected $dates = [
        'effective_from',
    ];

    protected $fillable = [
        'type', 'name', 'is_default', 'effective_from',
    ];

    /**
     * Returns all the users that have signed this instance of the AGB.
     *
     *  @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Fetches the currently active Agb.
     *
     * @param string $type
     * @return Agb|null
     */
    public static function current(string $type): ?self
    {
        /** @var self $model */
        $model = self::query()
            ->forType($type)
            ->isDefault()
            ->latest()
            ->first();

        return $model;
    }

    /**
     * Creates a human readable filename for this model
     * as the original filename is a random string.
     *
     * @return string
     */
    public function getReadableFilename(): string
    {
        return Str::slug($this->name ?: env('APP_NAME', 'AGB'), '_', app()->getLocale()).'.pdf';
    }

    /**
     * Creates an URL to access/download the file.
     *
     * @return string
     */
    public function getDownloadUrl(): string
    {
        return URL::signedRoute('agbs.download', [$this]);
    }

    /**s
     * Indicates if this model can be deleted.
     *
     * It cannot be deleted if:
     * - Users are depending on it
     * - It's marked as default and there is no other fallback
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return (! $this->is_default || self::hasEnoughDefaults()) && $this->users()->doesntExist();
    }

    protected static function hasEnoughDefaults(): bool
    {
        return self::query()->isDefault()->count() >= count(self::TYPES);
    }

    public function newEloquentBuilder($query): AgbBuilder
    {
        return new AgbBuilder($query);
    }
}
