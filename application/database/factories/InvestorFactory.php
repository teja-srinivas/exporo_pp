<?php

use Faker\Generator as Faker;

$factory->define(App\Investor::class, function (Faker $faker) {
    static $id = 1;

    return [
        'id' => $id++,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'last_user_id' => 0,
    ];
});
