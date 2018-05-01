<?php

use Faker\Generator as Faker;

$factory->define(App\Agb::class, function (Faker $faker) {
    return [
        'name' => $faker->date('d.m.Y'),
    ];
});
