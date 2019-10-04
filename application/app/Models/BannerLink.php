<?php

namespace App\Models;

use App\Helper\TagReplacer;
use App\Traits\HasLinkInstance;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $url
 * @property-read BannerSet $bannerSet
 */
class BannerLink extends Model
{
    use HasLinkInstance;

    public const MORPH_NAME = 'banner_link';

    protected $fillable = [
        'title',
        'url',
    ];

    public function bannerSet()
    {
        return $this->belongsTo(BannerSet::class, 'set_id');
    }

    public function getUrlForUser(User $user): string
    {
        return TagReplacer::replace($this->url, TagReplacer::getUserTags($user));
    }
}
