<?php

declare(strict_types=1);

use App\Models\Agb;
use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Agb::class, static function (Faker $faker) {
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

$factory->state(Agb::class, 'default', [
    'is_default' => true,
]);

foreach (Agb::TYPES as $type) {
    $factory->state(Agb::class, $type, [
        'type' => $type,
    ]);
}
