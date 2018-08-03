<?php
declare(strict_types=1);


namespace App\Console\Commands;


use App\User;
use App\UserDetails;

final class ImportPartnerCommand extends ImportCommand
{
    protected $signature = 'import:partner';
    protected $description = 'imports partner';

    protected $model = User::class;
    protected $apiUrl = 'exporo.dev/api/partnerprogram/partner';

    protected function importModel(array $partner): void
    {
        User::updateOrCreate(
            ['id' => $partner['id']],
            [
                'id' => $partner['id'],
                'first_name' => $partner['first_name'],
                'last_name' => $partner['last_name'],
                'email' => $partner['email'],
                'password' => $partner['password'],
                'updated_at' => $partner['created_at'],
                'accepted_at' => $partner['partner_approved_at'],
            ]
        );
        UserDetails::updateOrCreate(
            ['id' => $partner['id']],
            [
                'id' => $partner['id'],
                'birth_date' => $partner['dob'],
                'birth_place' => $partner['pob'],
                'address_street' => $partner['street'],
                'address_number' => $partner['street_no'],
                'address_addition' => $partner['street_additional'],
                'address_zipcode' => $partner['postal_code'],
                'address_city' => $partner['city'],
                'phone' => $partner['phone'],
                'website' => $partner['partner_url'],
                'vat_id' => $partner['tax_id'],
                'registration_bonus' => $partner['provision_registration'],
                'first_investment_bonus' => $partner['provision_first_investment'],
                'further_investment_bonus' => $partner['provision_investment'],
                'vat_included' => $partner['vat_included'],
                'vat_amount' => $partner['vat'],
            ]
        );
    }
}