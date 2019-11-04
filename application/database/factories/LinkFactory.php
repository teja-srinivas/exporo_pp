<?php

declare(strict_types=1);

use App\Models\Link;
use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Link::class, static function (Faker $faker) {
    return [
        'title' => $faker->words(3, true),
        'description' => $faker->text,
        'url' => $faker->url,
    ];
});
