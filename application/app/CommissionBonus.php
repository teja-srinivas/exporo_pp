<?php
declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float $value
 * @property string $calculation_type
 * @property Carbon $accepted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class CommissionBonus extends Model
{
    protected $table = 'commission_bonuses';

    protected $fillable = [
        'id', 'type_id', 'first_investment', 'further_investment', 'registration', 'user_id'
    ];

    protected $casts = [
        'value' => 'float',
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
