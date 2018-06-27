<?php

namespace App;

use App\Interfaces\FileReference;
use App\Traits\Dateable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\URL;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Agb extends Model implements AuditableContract, FileReference
{
    use Auditable;
    use Dateable;

    const DIRECTORY = 'agbs';

    const TYPE_AG = 'ag';
    const TYPE_GMBH = 'gmbh';

    const TYPES = [self::TYPE_AG, self::TYPE_GMBH];

    protected static $numberOfDefaults = null;

    protected $casts = [
        'is_default' => 'bool',
    ];

    protected $fillable = [
        'type', 'name', 'is_default'
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
            throw new \Exception("$type needs to be one of ". implode(', ', self::TYPES));
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
        return str_slug($this->name ?: env('APP_NAME', 'AGB'), '_', app()->getLocale()) . '.pdf';
    }

    /**
     * Creates an URL to access/download the file.
     *
     * @return string
     */
    public function getDownloadUrl(): string
    {
        return URL::temporarySignedRoute('agbs.download', now()->addHours(12), $this);
    }

    /**
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
