<?php
declare(strict_types=1);

namespace App\Models;

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
    const TYPE_REGISTRATION = 'registration';
    const TYPE_FIRST_INVESTMENT = 'first_investment';
    const TYPE_FURTHER_INVESTMENT = 'further_investment';

    const TYPES = [
        self::TYPE_REGISTRATION,
        self::TYPE_FIRST_INVESTMENT,
        self::TYPE_FURTHER_INVESTMENT,
    ];

    protected $table = 'commission_bonuses';

    protected $fillable = [
        'id', 'type_id', 'first_investment', 'further_investment', 'registration', 'user_id'
    ];

    protected $casts = [
        'value' => 'float',
    ];


    public function bundles()
    {
        return $this->belongsToMany(BonusBundle::class, 'bonus_bundle', 'bonus_id', 'bundle_id');
    }

    public function type()
    {
        return $this->belongsTo(CommissionType::class, 'type_id', 'id');
    }

    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
