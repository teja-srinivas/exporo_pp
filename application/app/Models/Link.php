<?php

namespace App\Models;

use App\LinkClick;
use Carbon\Carbon;
use App\LinkInstance;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Collection $instances
 * @property-read Collection $clicks
 */
class Link extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'title', 'description', 'url',
    ];

    public function instances()
    {
        return $this->hasMany(LinkInstance::class);
    }

    public function clicks()
    {
        return $this->hasManyThrough(LinkClick::class, LinkInstance::class);
    }

    public function getTextForUser(User $user)
    {
        $replacements = [
            '#reflink' => '?a_aid='.$user->id,
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->url
        );
    }
}
