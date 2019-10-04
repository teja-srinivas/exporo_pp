<?php

namespace App\Models;

use App\BannerLink;
use OwenIt\Auditing\Auditable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property Company $company
 * @property Collection $banners
 * @property Collection $links
 * @property string $title
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class BannerSet extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'title',
    ];

    protected $casts = [
        'urls' => 'array', // legacy config for change logs
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', null, 'companies');
    }

    public function banners()
    {
        return $this->hasMany(Banner::class, 'set_id');
    }

    public function links()
    {
        return $this->hasMany(BannerLink::class, 'set_id');
    }
}
