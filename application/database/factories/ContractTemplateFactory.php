<?php

use App\Models\Company;
use App\Models\ContractTemplate;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(ContractTemplate::class, static function (Faker $faker) {
    return [
        'name' => $faker->slug,
        'cancellation_days' => 30,
        'claim_years' => 5,
        'vat_included' => false,
        'vat_amount' => 0,
        'company_id' => static function (): int {
            /** @var Company $company */
            $company = factory(Company::class)->create();
            return $company->getKey();
        },
    ];
});