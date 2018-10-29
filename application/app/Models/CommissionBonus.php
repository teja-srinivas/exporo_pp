<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $type_id
 * @property int $user_id
 * @property float $value
 * @property bool $is_overhead
 * @property bool $is_percentage
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

    const DISPLAY_NAMES = [
        self::TYPE_REGISTRATION => 'Registrierung',
        self::TYPE_FIRST_INVESTMENT => 'Erstinvestment',
        self::TYPE_FURTHER_INVESTMENT => 'Folgeinvestment',
    ];

    protected $table = 'commission_bonuses';

    protected $fillable = [
        'id', 'type_id', 'first_investment', 'further_investment', 'registration', 'user_id'
    ];

    protected $casts = [
        'value' => 'float',
        'is_overhead' => 'bool',
        'is_percentage' => 'bool',
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

    public function getDisplayValue()
    {
        return $this->is_percentage ? ($this->value * 100) . '%' : format_money($this->value);
    }

    public function getDisplayName()
    {
        return self::DISPLAY_NAMES[$this->calculation_type];
    }
}
