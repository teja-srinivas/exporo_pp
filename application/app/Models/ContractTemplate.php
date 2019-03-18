<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string|null $body
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection $contracts
 */
class ContractTemplate extends Model
{
    protected $fillable = ['body'];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
