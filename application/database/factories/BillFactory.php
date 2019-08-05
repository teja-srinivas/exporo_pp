<?php

use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Bill::class, function (Faker $faker) {
    return [
        'released_at' => $faker->randomDigit > 3 ? $faker->date() : null,
    ];
});

$factory->state(\App\Models\Bill::class, 'released', function (Faker $faker) {
    return [
        'released_at' => $faker->dateTimeBetween(\App\Models\Commission::LAUNCH_DATE)->format('Y-m-d'),
        'pdf_created_at' => $faker->dateTimeBetween(\App\Models\Commission::LAUNCH_DATE)->format('Y-m-d'),
    ];
});
