<?php

namespace App;

use App\Models\BannerSet;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $url
 * @property-read BannerSet $bannerSet
 */
class BannerLink extends Model
{
    protected $fillable = [
        'title',
        'url',
    ];

    public function bannerSet()
    {
        return $this->belongsTo(BannerSet::class, 'set_id');
    }
}
