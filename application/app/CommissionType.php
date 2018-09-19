<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionType extends Model
{
    protected $table = 'commission_types';

    protected $fillable = [
        'id', 'user_id', 'name'
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
