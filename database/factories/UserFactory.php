<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'health_card_number' => mb_strtoupper(Str::random(4)) . ' '
            . $faker->randomNumber(4, true) . ' ' . $faker->randomNumber(4, true),
        'address' => $faker->address,
        'phone_number' => $faker->phoneNumber,
        'gender' => 'male',
        'birth_date' => $faker->date('Y-m-d', strtotime('- 18 year')),
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
