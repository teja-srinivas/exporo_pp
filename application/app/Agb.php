<?php

namespace App;

use App\Traits\Dateable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\URL;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Agb extends Model implements AuditableContract
{
    use Auditable;
    use Dateable;

    const DIRECTORY = 'agbs';

    protected static $numberOfDefaults = null;

    protected $casts = [
        'is_default' => 'bool',
    ];

    protected $fillable = [
        'name', 'is_default'
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
     * @return mixed
     */
    public static function current()
    {
        return self::isDefault()->latest()->first();
    }

    public function scopeIsDefault($query, bool $value = true)
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
        return URL::temporarySignedRoute('documents.download', now()->addHours(12), $this);
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
