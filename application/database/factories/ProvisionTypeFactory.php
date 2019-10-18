<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use App\Models\CommissionType;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(CommissionType::class, static function (Faker $faker) {
    return [
        'id' => $faker->unique()->numberBetween(),
        'name' => 'finanzierung',
    ];
});
