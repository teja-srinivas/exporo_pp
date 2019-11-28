<?php

declare(strict_types=1);

use App\Models\Company;
use Faker\Generator as Faker;
use App\Models\PartnerContract;
use App\Models\PartnerContractTemplate;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(PartnerContractTemplate::class, static function (Faker $faker) {
    return [
        'name' => $faker->slug,
        'type' => PartnerContract::STI_TYPE,
        'cancellation_days' => 30,
        'claim_years' => 5,
        'company_id' => static function (): int {
            /** @var Company $company */
            $company = factory(Company::class)->create();

            return $company->getKey();
        },
    ];
});
