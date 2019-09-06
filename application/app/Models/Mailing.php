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
 * @property string $text
 * @property string $html
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Mailing extends Model implements AuditableContract
{
    use Auditable;

    protected $auditExclude = [
        'html',
    ];

    protected $fillable = [
        'title', 'description', 'text', 'html',
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

    public function getHtmlForUser(User $user): string
    {
        $replacements = [
            '%partnername%' => implode(' ', array_filter([trim($user->first_name), trim($user->last_name)])),
            '%partnerid%' => (string) $user->id,
            '%reflink%' => "?a_aid={$user->id}",
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $this->html
        );
    }
}
