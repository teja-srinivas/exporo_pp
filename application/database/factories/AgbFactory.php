<?php

use App\Models\Agb;
use Faker\Generator as Faker;

$factory->define(\App\Models\Agb::class, function (Faker $faker) {
    $created = $faker->date();

    return [
        'type' => $faker->randomElement(Agb::TYPES),
        'name' => $created,
        'filename' => '', // non-existent file
        'is_default' => false,
        'created_at' => $created,
        'updated_at' => $created,
    ];
});
