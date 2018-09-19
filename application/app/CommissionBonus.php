<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionBonus extends Model
{
    protected $table = 'commission_bonuses';

    protected $fillable = [
        'id', 'type_id', 'first_investment', 'further_investment', 'registration', 'user_id'
    ];

    protected $casts = [
        'first_investment' => 'float',
        'further_investment' => 'float',
        'registration' => 'float'
    ];

    public function commissionTypes()
    {
        return $this->belongsTo(CommissionType::class, 'type_id', 'id');
    }

    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
