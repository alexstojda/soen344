<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use App\Models\Availability;
use App\Models\Doctor;

$factory->define(Availability::class, function (Faker $faker) {
    $randomDate = $faker->dateTimeBetween('last month', 'next year');
    $randomTimestamp = random_int($randomDate->setTime(config('bonmatin.office_hours.open'), 0)
        ->getTimestamp(), (clone $randomDate)->setTime(config('bonmatin.office_hours.close'), 0)
        ->getTimestamp());
    $start = Carbon::createFromTimestamp($randomTimestamp)->startOfHour();
    $end = $start->copy()->add($faker->randomElement(['20 minutes','40 minutes', '1 hour', '2 hours']));
    $available = $faker->boolean(100);
    return [
        'doctor_id' => Doctor::all()->random(),
        'start' => $start,
        'end' => $end,
        'is_working' => $available,
        'message' => $available ? null : $faker->sentence(8),
    ];
});
