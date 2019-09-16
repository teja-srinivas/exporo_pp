<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\CommissionType::class, static function (Faker $faker) {
    return [
        'id' => $faker->unique()->numberBetween(),
        'name' => 'finanzierung',
    ];
});
