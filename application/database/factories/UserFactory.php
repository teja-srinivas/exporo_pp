<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'api_token' => Str::random(64),
        'remember_token' => Str::random(10),
        'accepted_at' => $faker->randomDigit > 2 ? $faker->dateTime : null,
        'rejected_at' => $faker->randomDigit > 8 ? $faker->dateTime : null,
    ];
});
