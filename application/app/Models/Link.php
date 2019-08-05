<?php

namespace App\Models;

use Carbon\Carbon;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Link extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'title', 'description', 'url',
    ];

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
