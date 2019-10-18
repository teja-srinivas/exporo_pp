<?php

declare(strict_types=1);

use App\Models\Schema;
use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Schema::class, static function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'description' => $faker->sentence,
        'formula' => 'x *  (z / 24) * (a * y)',
    ];
});
