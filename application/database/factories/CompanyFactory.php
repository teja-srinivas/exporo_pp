<?php

use App\Models\Company;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

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
