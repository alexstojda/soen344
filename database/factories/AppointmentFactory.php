<?php

use Faker\Generator as Faker;

$factory->define(App\Appointment::class, function (Faker $faker) {
    $doc = \App\Doctor::all()->random();
    $availability = $doc->availabilities->random();
    return [
        'doctor_id' => $doc->id,
        'patient_id' => \App\User::all()->random(),
        'room_id' => \App\Room::all()->random(),
        'start' => $availability->start,
        'end' => $availability->end,
        'type' => $faker->randomElement(['walk-in', 'annual checkup']),
        'status' => $faker->randomElement(['cart' ,'active', 'rescheduled', 'complete', 'cancelled']),
    ];
});
