<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Doctor;

$factory->define(Doctor::class, function (Faker $faker) {
    return [
        'permit_id' => $faker->randomNumber(7, true),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'speciality' => 'Family Doctor',
        'city' => $faker->city,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'clinic_id' => \App\Models\Clinic::all()->random()->id,
    ];
});
