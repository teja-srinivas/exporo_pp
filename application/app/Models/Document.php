<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Str;
use OwenIt\Auditing\Auditable;
use App\Interfaces\FileReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property string $name
 * @property string $filename
 * @property string $description
 */
class Document extends Model implements FileReference, AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    public const DIRECTORY = 'documents';

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
        return Str::slug($this->name, '_', app()->getLocale()).'.pdf';
    }

    public function getDownloadUrl(): string
    {
        $expiration = now()->addHour();

        $extension = pathinfo($this->filename, PATHINFO_EXTENSION);

        return Storage::disk(self::DISK)->temporaryUrl($this->filename, $expiration, [
            'ResponseContentDisposition' => "inline; filename=\"{$this->name}.{$extension}\"",
        ]);
    }
}
