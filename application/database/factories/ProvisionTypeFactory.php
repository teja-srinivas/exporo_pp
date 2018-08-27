<?php

use Faker\Generator as Faker;

$factory->define(App\ProvisionType::class, function (Faker $faker) {

    return [
        'id' => $faker->unique()->numberBetween(),
        'name' => 'finanzierung',
    ];
});
