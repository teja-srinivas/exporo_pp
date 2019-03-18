<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $text
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Mailing extends Model implements AuditableContract
{
    use Auditable;

    protected $fillable = [
        'title', 'description', 'text',
    ];

    public function getTextForUser(User $user)
    {
        $replacements = [
            '#partnername' => implode(' ', array_filter([trim($user->first_name), trim($user->last_name)])),
            '#partnerid' => (string) $user->id,
            '#reflink' => "?a_aid={$user->id}",
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->text
        );
    }
}
