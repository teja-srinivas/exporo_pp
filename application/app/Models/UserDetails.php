<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use App\Traits\Encryptable;
use OwenIt\Auditing\Auditable;
use App\Events\UserDetailsUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @property User $user
 * @property bool $vat_included
 * @property float $vat_amount
 * @property string $company
 * @property string $iban
 * @property string $vat_id
 * @property string $address_zipcode
 * @property string $address_city
 * @property string $address_number
 * @property string $address_street
 * @property string $bic
 * @property string $title
 * @property string $display_name
 * @property string $salutation
 * @property Carbon $birth_date
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
        'bic',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Indicates if this user is from the given country using an ISO country code (e.g. DE, AT, ...).
     * This uses the VAT ID, since we don't ask for an actual country upon registration.
     *
     * @param  string  $countryCode
     * @return bool
     */
    public function isFromCountry(string $countryCode): bool
    {
        // check if VAT ID starts with the given code
        return stripos($this->vat_id, $countryCode) === 0;
    }

    /**
     * Formats the IBAN by placing a space after every 4th character.
     *
     * @return string|null The formatted IBAN or null
     */
    public function getFormattedIban(): ?string
    {
        $iban = $this->iban;

        if ($iban === null) {
            return null;
        }

        $concat = str_replace(' ', '', $iban);

        return wordwrap($concat, 4, ' ', true);
    }
}
