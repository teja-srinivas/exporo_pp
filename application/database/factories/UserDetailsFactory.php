<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\UserDetails::class, static function (Faker $faker) {
    return [
        'salutation' => $faker->randomElement(['male', 'female']),
        'birth_date' => $faker->dateTimeBetween('-60 years', '-25 years'),
        'birth_place' => $faker->city,
        'address_street' => $faker->streetName,
        'address_number' => $faker->buildingNumber,
        'address_zipcode' => $faker->postcode,
        'address_city' => $faker->city,
        'phone' => $faker->phoneNumber,
        'website' => $faker->url,
    ];
});
