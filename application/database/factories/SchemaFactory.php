<?php

use App\Models\Schema;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(Schema::class, static function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'description' => $faker->sentence,
        'formula' => 'x *  (z / 24) * (a * y)',
    ];
});
