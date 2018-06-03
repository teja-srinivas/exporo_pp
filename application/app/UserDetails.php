<?php

namespace App;

use App\Rules\VatId;
use App\Traits\Dateable;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
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


    public static function getValidationRules($required = true)
    {
        $prefix = $required ? 'required' : 'nullable';

        $adultYear = now()->subYears(18);

        return [
            'company' => 'nullable|string|max:100',
            'title' => ['nullable', Rule::in(User::TITLES)],
            'salutation' => "{$prefix}|in:male,female",
            "birth_day" => "{$prefix}|numeric|min:1|max:31",
            "birth_month" => "{$prefix}|numeric|min:1|max:12",
            "birth_year" => "{$prefix}|numeric|min:". now()->subYears(120)->year . '|max:' . $adultYear->year,
            'birth_date' => "{$prefix}|date|before_or_equal:" . $adultYear, // needs to be an adult
            'birth_place' => "{$prefix}|string|max:100",
            'address_street' => 'nullable|string|max:100',
            'address_number' => 'nullable|string|max:20',
            'address_addition' => 'nullable|string|max:100',
            'address_zipcode' => 'nullable|string|max:20',
            'address_city' => 'nullable|string|max:100',
            'phone' => "{$prefix}|string|max:100",
            'website' => 'nullable|string|max:100',
            'vat_id' => ['nullable', new VatId()],
            'tax_office' => 'nullable|string|max:100',
        ];
    }

    // Fix for the audit package not detecting this method (for some reason)
    protected function asDateTime($value)
    {
        return $this->parentAsDateTime($value);
    }
}
