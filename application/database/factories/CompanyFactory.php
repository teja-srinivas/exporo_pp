<?php

declare(strict_types=1);

use App\Models\Company;
use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Company::class, static function (Faker $faker) {
    return [
        'name' => $faker->company,
        'city' => $faker->city,
        'email' => $faker->email,
        'fax_number' => $faker->phoneNumber,
        'phone_number' => $faker->phoneNumber,
        'postal_code' => $faker->postcode,
        'street' => $faker->streetName,
        'street_no' => $faker->streetSuffix,
        'website' => $faker->url,
    ];
});
