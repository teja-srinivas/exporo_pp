<?php

use Faker\Generator as Faker;

$factory->define(App\Investment::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->numberBetween(),
        'investor_id' => $faker->numberBetween(0, 100),
        'ext_user_id' => $faker->numberBetween(0, 1000),
        'project_id' => $faker->numberBetween(0, 100),
        'amount' => $faker->numberBetween(500, 1500),
        'interest_rate' => $faker->randomFloat(2, 0.8, 1.5),
        'bonus' => $faker->numberBetween(0, 50),
        'type' => $faker->word,
        'paid_at' => $faker->randomDigit > 3 ? $faker->dateTime : null,
        'is_first_investment' => $faker->randomDigit > 8,
    ];
});
