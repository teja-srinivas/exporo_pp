<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use App\Helper\Rules;
use Parental\HasChildren;
use App\Events\ContractUpdated;
use App\Builders\ContractBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;

/**
 * @method static ContractBuilder query()
 *
 * @property int $id
 * @property string $type
 * @property int $user_id
 * @property int $template_id
 * @property string $special_agreement
 * @property Carbon $accepted_at The date the user fully accepted the contract.
 * @property Carbon $released_at The date we confirmed the contract, but has not yet been accepted by the user.
 * @property Carbon $terminated_at The date the contract has been terminated (either by hand or automation).
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property ContractTemplate $template
 * @property User $user
 */
class Contract extends Model
{
    use HasChildren;
    use OptimusEncodedRouteKey;

    public const TYPES = [
        PartnerContract::STI_TYPE,
        ProductContract::STI_TYPE,
    ];

    protected $childTypes = [
        PartnerContract::STI_TYPE => PartnerContract::class,
        ProductContract::STI_TYPE => ProductContract::class,
    ];

    protected $dispatchesEvents = [
        'updated' => ContractUpdated::class,
    ];

    protected $casts = [
        'cancellation_days' => 'int',
        'claim_years' => 'int',
        'vat_included' => 'bool',
        'vat_amount' => 'float',
        'is_exclusive' => 'bool',
        'allow_overhead' => 'bool',
    ];

    protected $dates = [
        'accepted_at',
        'released_at',
        'terminated_at',
    ];

    protected $fillable = [
        'cancellation_days',
        'claim_years',
        'special_agreement',
        'vat_included',
        'vat_amount',
        'is_exclusive',
        'allow_overhead',
        'released_at',
        'type',
    ];

    public function getTitle(): string
    {
        return __("contracts.{$this->type}.title");
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContractTemplate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return !$this->isEditable() && ($this->terminated_at === null || $this->terminated_at > now());
    }

    public function isEditable(): bool
    {
        return $this->accepted_at === null;
    }

    public function isReleased(): bool
    {
        return $this->released_at !== null;
    }

    public function getValidationRules(): array
    {
        return Rules::byPermission([
            'management.contracts.update-special-agreement' => [
                'special_agreement' => ['nullable'],
            ],
        ]);
    }

    public function newEloquentBuilder($query): ContractBuilder
    {
        return new ContractBuilder($query);
    }

    public static function getTypeForClass(string $class): string
    {
        return array_search($class, (new Contract())->childTypes) ?? $class;
    }
}
