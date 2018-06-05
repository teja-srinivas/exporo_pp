<?php

namespace App;

use App\Traits\Dateable;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class UserDetails extends Model implements AuditableContract
{
    use Encryptable;
    use Auditable;
    use Dateable {
        asDateTime as parentAsDateTime;
    }

    public $incrementing = false;

    protected $fillable = [
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
    ];

    protected $dates = [
        'birth_date',
    ];

    protected $hidden = [
        'vat_id',
    ];

    protected $encryptable = [
        'vat_id',
    ];


    // Fix for the audit package not detecting this method (for some reason)
    protected function asDateTime($value)
    {
        return $this->parentAsDateTime($value);
    }
}
