<?php

use Faker\Generator as Faker;

$factory->define(App\Investment::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->numberBetween(),
        'amount' => $faker->numberBetween(500, 1500),
        'interest_rate' => $faker->randomFloat(2, 0.8, 1.5),
        'bonus' => $faker->numberBetween(0, 50),
        'paid_at' => $faker->randomDigit > 3 ? $faker->dateTime : null,
        'is_first_investment' => $faker->randomDigit > 8,
        'acknowledged_at' => $faker->randomDigit > 5 ? $faker->dateTime : null,
        'cancelled_at' => $faker->randomDigit > 9 ? $faker->dateTime : null,
    ];
});
