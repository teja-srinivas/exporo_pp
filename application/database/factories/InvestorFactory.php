<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Investor::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->randomNumber(),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});
