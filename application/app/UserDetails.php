<?php

namespace App;

use App\Traits\Dateable;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class UserDetails extends Model implements AuditableContract
{
    use Encryptable;
    use Auditable;
    use Dateable {
        asDateTime as parentAsDateTime;
    }

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
        'further_investment_bonus'
    ];

    protected $dates = [
        'birth_date',
    ];

    protected $hidden = [
        'vat_id',
    ];

    protected $encryptable = [
        'vat_id',
        'birth_place',
        'address_street',
        'address_addition',
        'address_zipcode',
        'address_city',
        'phone',
        'website',
        'tax_office'
    ];


    // Fix for the audit package not detecting this method (for some reason)
    protected function asDateTime($value)
    {
        return $this->parentAsDateTime($value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
