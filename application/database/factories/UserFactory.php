<?php

use App\Policies;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factory */

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'api_token' => Str::random(64),
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'verified', function (Faker $faker) {
    return [
        'email_verified_at' => $faker->dateTime,
    ];
});

$factory->state(User::class, 'accepted', function (Faker $faker) {
    return [
        'email_verified_at' => $faker->dateTime,
        'accepted_at' => $faker->dateTime,
    ];
});

$factory->state(User::class, 'rejected', function (Faker $faker) {
    return [
        'email_verified_at' => $faker->dateTime,
        'rejected_at' => $faker->dateTime,
    ];
});

$factory->afterCreatingState(User::class, 'billable', function (User $user) {
    $user->givePermissionTo(Policies\BillPolicy::CAN_BE_BILLED_PERMISSION);
});
