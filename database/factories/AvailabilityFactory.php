<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use App\Models\Availability;
use App\Models\Doctor;

$factory->define(Availability::class, function (Faker $faker) {
    $doc = Doctor::inRandomOrder()->first();
    $randomDate = $faker->dateTimeBetween('last month', 'next year');
    $randomTimestamp = random_int(
        $randomDate->setTime($doc->clinic->open_time[0], $doc->clinic->open_time[1], $doc->clinic->open_time[2])
        ->getTimestamp(),
        (clone $randomDate)->setTime($doc->clinic->close_time[0] - 1, $doc->clinic->close_time[1], $doc->clinic->close_time[2])
        ->getTimestamp()
    );
    $start = Carbon::createFromTimestamp($randomTimestamp)->startOfHour();
    $end = $start->copy()->add($faker->randomElement(['20 minutes', '40 minutes', '1 hour', '80 minutes', '2 hours']));
    if ($end->hour > $doc->clinic->close_time[0]) {
        $diff = $start->diffInMinutes($end);
        $end->ceilHour($doc->clinic->close_time[0]);
        $start = $end->subMinutes($diff);
    }
    $available = $faker->boolean(100);
    return [
        'doctor_id' => $doc->id,
        'start' => $start,
        'end' => $end,
        'is_working' => $available,
        'message' => $available ? null : $faker->sentence(8),
    ];
});
