<?php

namespace App;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use Encryptable;

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

    protected $encryptable = [
        'vat_id',
    ];
}
