<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_project_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CommissionType extends Model
{
    protected $table = 'commission_types';

    protected $fillable = [
        'id', 'name', 'is_project_type',
    ];

    protected $casts = [
        'is_project_type' => 'bool',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'commission_type');
    }

    public function bonuses()
    {
        return $this->hasOne(CommissionBonus::class, 'type_id', 'id');
    }
}
