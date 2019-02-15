<?php

namespace App\Models;

use App\Events\UserDetailsUpdated;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property User $user
 * @property bool $vat_included
 * @property float $vat_amount
 * @property string $iban
 * @property string $bic
 */
class UserDetails extends Model implements AuditableContract
{
    use Encryptable;
    use Auditable;

    public $incrementing = false;

    protected $dispatchesEvents = [
        'updated' => UserDetailsUpdated::class,
    ];

    protected $casts = [
        'vat_amount' => 'float',
        'vat_included' => 'bool',
    ];

    protected $fillable = [
        'id',
        'company',
        'display_name',
        'title',
        'salutation',
        'birth_date',
        'birth_place',
        'address_street',
        'address_number',
        'address_addition',
        'address_zipcode',
        'address_city',
        'phone',
        'website',
        'vat_id',
        'vat_amount',
        'vat_included',
        'tax_office',
        'registration_bonus',
        'first_investment_bonus',
        'further_investment_bonus',
        'bic',
        'iban',
    ];

    protected $dates = [
        'birth_date',
    ];

    protected $encryptable = [
        'vat_id',
        'company',
        'birth_place',
        'address_street',
        'address_number',
        'address_addition',
        'address_zipcode',
        'address_city',
        'phone',
        'website',
        'tax_office',
        'iban',
        'bic'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
