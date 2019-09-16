<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Schema::class, static function (Faker $faker) {
    return [
        'name' => $faker->title,
        'description' => $faker->sentence,
        'formula' => 'x *  (z / 24) * (a * y)',
    ];
});
