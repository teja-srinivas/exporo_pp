<?php

namespace App\Models;

use App\BannerLink;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property Company $company
 * @property Collection $banners
 * @property string $title
 * @property array $urls
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class BannerSet extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'title', 'urls',
    ];

    protected $casts = [
        'urls' => 'array',
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

    public function getUrlForUser(string $url, User $user): string
    {
        $replacements = [
            '#reflink' => '?a_aid='.$user->id,
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $url
        );
    }
}
