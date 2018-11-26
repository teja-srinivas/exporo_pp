<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property bool $vat_included
 */
class UserDetails extends Model implements AuditableContract
{
    use Encryptable;
    use Auditable;

    public $incrementing = false;

    protected $casts = [
        'first_investment_bonus' => 'float',
        'further_investment_bonus' => 'float',
        'registration_bonus' => 'float',
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

    protected $hidden = [
        'vat_id',
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
