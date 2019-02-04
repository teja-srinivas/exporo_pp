<?php

namespace App\Models;

use App\Interfaces\FileReference;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Document extends Model implements FileReference, AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    const DIRECTORY = 'documents';

    public $disk = 's3';

    protected $fillable = [
        'name', 'description',
    ];


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Creates a human readable filename for this model
     * as the original filename is a random string.
     *
     * @return string
     */
    public function getReadableFilename(): string
    {
        return str_slug($this->name, '_', app()->getLocale()) . '.pdf';
    }

    public function getDownloadUrl(): string
    {
        $expiration = now()->addHour();

        return Storage::disk($this->disk)->temporaryUrl($this->filename, $expiration);
    }
}
