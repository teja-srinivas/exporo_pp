<?php
declare(strict_types=1);

namespace App\Models;

use App\Relationships\BelongsToOne;
use App\Events\CommissionBonusUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

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
 *
 * @property BonusBundle $bundle
 * @property Collection $bundles
 * @property CommissionType $type
 * @property Contract $contract
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
        'id', 'type_id', 'calculation_type', 'value', 'is_overhead', 'is_percentage', 'contract_id',
    ];

    protected $casts = [
        'value' => 'float',
        'is_overhead' => 'bool',
        'is_percentage' => 'bool',
    ];

    protected $dispatchesEvents = [
        'updated' => CommissionBonusUpdated::class,
    ];


    public function bundles()
    {
        return $this->belongsToMany(BonusBundle::class, 'bonus_bundle', 'bonus_id', 'bundle_id');
    }

    public function bundle()
    {
        $bundle = new BonusBundle();

        return new BelongsToOne(
            $bundle->newQuery(), $this,
            'bonus_bundle', 'bonus_id', 'bundle_id',
            $this->getKeyName(), $bundle->getKeyName()
        );
    }

    public function type()
    {
        return $this->belongsTo(CommissionType::class, 'type_id', 'id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id', 'contract');
    }

    public function getDisplayValue()
    {
        return $this->is_percentage ? ($this->value * 100) . '%' : format_money($this->value);
    }

    public function getDisplayName()
    {
        return self::DISPLAY_NAMES[$this->calculation_type];
    }


    /**
     * Creates the attributes required for a "value" bonus.
     *
     * @param string $type
     * @param float $value
     * @param bool $overhead
     * @return array
     */
    public static function value(string $type, float $value, bool $overhead = false): array
    {
        self::validateType($type);

        return [
            'calculation_type' => $type,
            'value' => $value,
            'is_percentage' => false,
            'is_overhead' => $overhead,
        ];
    }

    /**
     * Creates the attributes required for a "percentage" bonus.
     *
     * @param string $type
     * @param float $value
     * @param bool $overhead
     * @return array
     */
    public static function percentage(string $type, float $value, bool $overhead = false): array
    {
        self::validateType($type);

        return [
            'calculation_type' => $type,
            'value' => $value,
            'is_percentage' => true,
            'is_overhead' => $overhead,
        ];
    }

    /**
     * Checks if the given type is in our list of allowed values.
     *
     * @param string $type
     * @throws InvalidArgumentException
     */
    protected static function validateType(string $type): void
    {
        if (! in_array($type, self::TYPES)) {
            throw new InvalidArgumentException("Invalid bonus type: $type");
        }
    }
}
