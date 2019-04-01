<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property bool $selectable
 * @property string $name
 * @property Collection $bonuses
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class BonusBundle extends Model
{
    protected $table = 'bundles';

    protected $casts = [
        'selectable' => 'bool',
    ];

    protected $fillable = [
        'name', 'selectable', 'child_user_selectable',
    ];


    public function bonuses()
    {
        return $this->belongsToMany(CommissionBonus::class, 'bonus_bundle', 'bundle_id', 'bonus_id');
    }

    public function scopeSelectable(Builder $query, bool $childUser = false)
    {
        $query->where($childUser ? 'child_user_selectable' : 'selectable', true);
    }
}
