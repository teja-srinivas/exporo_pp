<?php

use Faker\Generator as Faker;

$factory->define(App\Bill::class, function (Faker $faker) {
    return [
        'released_at' => $faker->randomDigit > 3 ? $faker->date() : null,
    ];
});
