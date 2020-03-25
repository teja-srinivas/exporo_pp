<?php

declare(strict_types=1);

namespace App\Models;

use App\LinkInstance;
use App\Traits\HasLinkInstance;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    use HasLinkInstance;

    public $disk = 's3';

    public const MORPH_NAME = 'campaign_link';

    protected $fillable = [
        'image_url',
        'document_url',
        'title',
        'description',
        'url',
        'is_active',
        'all_users',
        'is_blacklist',
        'started_at',
        'ended_at',
    ];

    protected $dates= [
        'started_at',
        'ended_at',
    ];

    /**
     * Returns a list of link instances that redirect to the proper URL.
     *
     * @return MorphMany
     */
    public function instances(): MorphMany
    {
        return $this->morphMany(LinkInstance::class, 'link');
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getImagePath(): string
    {
        return "campaigns/images";
    }

    public function getDocumentPath(): string
    {
        return "campaigns/documents";
    }

    public function getImageDownloadUrl(): ?string
    {
        return $this->image_url !== null ? Storage::disk($this->disk)->url($this->image_url) : null;
    }

    public function getDocumentDownloadUrl(): ?string
    {
        return $this->document_url !== null ? Storage::disk($this->disk)->url($this->document_url) : null;
    }
}
