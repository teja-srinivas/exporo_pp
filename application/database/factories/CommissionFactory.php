<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Commission::class, function (Faker $faker) {
    return [
        'net' => 0,
        'gross' => 0,
        'bonus' => 0,
    ];
});
