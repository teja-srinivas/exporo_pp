<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use RuntimeException;
use Parental\HasChildren;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string|null $body
 * @property bool $is_default
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Company $company
 * @property Collection $contracts
 */
class ContractTemplate extends Model
{
    use HasChildren;

    protected $childTypes = [
        PartnerContract::STI_TYPE => PartnerContractTemplate::class,
        ProductContract::STI_TYPE => ProductContractTemplate::class,
    ];

    protected $fillable = [
        'body', 'name', 'is_default',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * @param  User  $user
     * @return Contract|void
     */
    public function createInstance(User $user)
    {
        throw new RuntimeException('Method not implemented');
    }
}
