<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property BannerSet $set
 * @property int $width
 * @property int $height
 * @property string $filename
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class Banner extends Model
{
    public $disk = 's3';

    protected $fillable = [
        'filename', 'width', 'height',
    ];

    public function set()
    {
        return $this->belongsTo(BannerSet::class, 'set_id', null, 'banner_sets');
    }

    public function getStoragePath(): string
    {
        return "{$this->set->company_id}/banners";
    }

    public function getDownloadUrl(): string
    {
        return Storage::disk($this->disk)->url($this->filename);
    }
}
