<?php

declare(strict_types=1);

use App\Models\Mailing;
use Faker\Generator as Faker;

/** @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Mailing::class, static function (Faker $faker) {
    return [
        'title' => $faker->words(3, true),
        'text' => $faker->text,
        'html' => $faker->randomHtml(1),
    ];
});
