<?php

use App\Models\Investor;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(Investor::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->randomNumber(),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

$factory->state(Investor::class, 'commissionable', [
    'claim_end' => now()->addCentury(),
]);
