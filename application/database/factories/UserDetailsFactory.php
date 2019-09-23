<?php

use App\Models\UserDetails;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(UserDetails::class, static function (Faker $faker) {
    return [
        'company' => $faker->boolean ? $faker->company : null,
        'salutation' => $faker->randomElement(['male', 'female']),
        'birth_date' => $faker->dateTimeBetween('-60 years', '-25 years'),
        'birth_place' => $faker->city,
        'address_street' => $faker->streetName,
        'address_number' => $faker->buildingNumber,
        'address_zipcode' => $faker->postcode,
        'address_city' => $faker->city,
        'phone' => $faker->phoneNumber,
        'website' => $faker->url,
        'iban' => $faker->optional()->iban('DE'),
        'bic' => $faker->optional()->swiftBicNumber,
    ];
});
