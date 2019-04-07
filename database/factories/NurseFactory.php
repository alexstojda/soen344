<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Nurse;

$factory->define(Nurse::class, function (Faker $faker) {
    return [
        'access_id' => mb_strtoupper($faker->randomLetter . $faker->randomLetter . $faker->randomLetter)
            . $faker->randomNumber(5, true),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
