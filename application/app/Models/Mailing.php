<?php

namespace App\Models;

use Carbon\Carbon;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Helper\TagReplacer;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $text
 * @property string $html
 * @property array $variables
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
        'title', 'description', 'text', 'html', 'variables'
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    public function getTextForUser(User $user)
    {
        return TagReplacer::replace(
            $this->text,
            TagReplacer::getUserTags($user) + $this->getCustomVariableReplacements($user)
        );
    }

    public function getHtmlForUser(User $user): string
    {
        return TagReplacer::replace(
            $this->html,
            TagReplacer::getUserTags($user) + $this->getCustomVariableReplacements($user)
        );
    }

    private function getCustomVariableReplacements(User $user): array
    {
        $replacements = [];

        foreach (($this->variables ?? []) as $variable) {
            switch ($variable['type']) {
                case 'link':
                    $replacements[$variable['placeholder']] = TagReplacer::replace(
                        $variable['url'],
                        TagReplacer::getUserTags($user)
                    );
                    break;
            }
        }

        return $replacements;
    }
}
