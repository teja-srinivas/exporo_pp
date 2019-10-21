<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\ContractTemplate;
use Faker\Generator as Faker;
use App\Models\ProductContractTemplate;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(ProductContractTemplate::class, static function (Faker $faker) {
    return [
        'name' => $faker->slug,
        'vat_included' => false,
        'vat_amount' => 0,
        'company_id' => static function (): int {
            /** @var Company $company */
            $company = factory(Company::class)->create();

            return $company->getKey();
        },
    ];
});
