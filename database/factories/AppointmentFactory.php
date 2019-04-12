<?php

use Faker\Generator as Faker;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Room;
use App\Models\User;

$factory->define(Appointment::class, function (Faker $faker) {
    return [
        'doctor_id' => Doctor::all()->random()->id,
        'patient_id' => User::all()->random()->id,
        'room_id' => Room::all()->random()->id,
        'type' => $faker->randomElement(['walk-in', 'checkup']),
        'status' => 'unscheduled',
        'paid' => $faker->boolean(75),
    ];
});
