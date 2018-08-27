<?php

use Faker\Generator as Faker;

$factory->define(App\Schema::class, function (Faker $faker) {
    return [
        'name' => $faker->title,
        'description' => $faker->sentence,
        'formula' => 'x *  (z / 24) * (a * y)',
    ];
});
