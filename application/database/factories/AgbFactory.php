<?php

use Faker\Generator as Faker;

$factory->define(App\Agb::class, function (Faker $faker) {
    $created = $faker->date();

    return [
        'name' => $created,
        'filename' => '', // non-existent file
        'is_default' => false,
        'created_at' => $created,
        'updated_at' => $created,
    ];
});
