<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    public $disk = 's3';

    protected $fillable = [
        'image_url',
        'document_url',
        'title',
        'description',
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
//TODO add      Storage::disk($this->disk)->
    public function getImageDownloadUrl(): ?string
    {
        return $this->image_url !== null ? url($this->image_url) : null;
    }
//TODO add      Storage::disk($this->disk)->
    public function getDocumentDownloadUrl(): ?string
    {
        return $this->document_url !== null ? url($this->document_url) : null;
    }
}
