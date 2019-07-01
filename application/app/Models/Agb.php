<?php

namespace App\Models;

use App\Interfaces\FileReference;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Agb extends Model implements AuditableContract, FileReference
{
    use Auditable;
    use OptimusEncodedRouteKey;

    const DIRECTORY = 'agbs';

    const TYPE_AG = 'ag';
    const TYPE_GMBH = 'gmbh';

    const TYPES = [self::TYPE_AG, self::TYPE_GMBH];

    protected static $numberOfDefaults = null;

    protected $casts = [
        'is_default' => 'bool',
    ];

    protected $dates = [
        'effective_from'
    ];

    protected $fillable = [
        'type', 'name', 'is_default', 'effective_from'
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
    public static function current(string $type): ?Agb
    {
        return self::isDefault()->forType($type)->latest()->first();
    }

    public function scopeForType(Builder $query, string $type)
    {
        if (!in_array($type, self::TYPES)) {
            // Silently fail the query in case it's not a valid type
            $type = null;
        }

        $query->where('type', $type);
    }

    public function scopeIsDefault(Builder $query, bool $value = true)
    {
        $query->where('is_default', $value);
    }

    /**
     * Creates a human readable filename for this model
     * as the original filename is a random string.
     *
     * @return string
     */
    public function getReadableFilename(): string
    {
        return Str::slug($this->name ?: env('APP_NAME', 'AGB'), '_', app()->getLocale()) . '.pdf';
    }

    /**
     * Creates an URL to access/download the file.
     *
     * @return string
     */
    public function getDownloadUrl(): string
    {
        return URL::SignedRoute('agbs.download', [$this]);
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
        return $this->users->count() === 0 && (!$this->is_default || $this->numberOfAvailableDefaults() > 1);
    }

    protected function numberOfAvailableDefaults()
    {
        return self::$numberOfDefaults ?: (self::$numberOfDefaults = self::isDefault()->count());
    }
}
