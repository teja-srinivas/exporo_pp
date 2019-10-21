<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cog\Laravel\Optimus\Traits\OptimusEncodedRouteKey;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string|null $city
 * @property string|null $email
 * @property string|null $fax_number
 * @property string|null $phone_number
 * @property string|null $postal_code
 * @property string|null $street
 * @property string|null $street_no
 * @property string|null $website
 * @property int $default_contract_template_id
 *
 * @property ContractTemplate $contractTemplate
 * @property Collection $bannerSets
 * @property Collection $users
 */
class Company extends Model implements AuditableContract
{
    use Auditable;
    use OptimusEncodedRouteKey;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function contractTemplate(): HasOne
    {
        return $this->hasOne(ProductContractTemplate::class);
    }

    public function bannerSets(): HasMany
    {
        return $this->hasMany(BannerSet::class);
    }
}
