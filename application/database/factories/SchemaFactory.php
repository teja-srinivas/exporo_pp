<?php

use Faker\Generator as Faker;

$factory->define(App\Schema::class, function (Faker $faker) {
    return [
        'name' => $faker->title,
        'description' => $faker->sentence,
        'formula' => 'x * 2 + y', // TODO change to a "real" fake formula
    ];
});
