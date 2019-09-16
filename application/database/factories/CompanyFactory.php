<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Company::class, static function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});
