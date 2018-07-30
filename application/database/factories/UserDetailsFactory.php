<?php

use Faker\Generator as Faker;

$factory->define(App\UserDetails::class, function (Faker $faker) {
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
        'first_investment_bonus' => $faker->randomFloat(2, 1, 15),
        'further_investment_bonus' => $faker->randomFloat(2, 0, 4),
    ];
});
