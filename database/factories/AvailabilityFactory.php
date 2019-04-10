<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use App\Models\Availability;
use App\Models\Doctor;

$factory->define(Availability::class, function (Faker $faker) {
    $doc = Doctor::inRandomOrder()->first();
    $open = $doc->clinic->open_time;
    $close = $doc->clinic->close_time;
    $randomDate = $faker->dateTimeBetween('last month', 'next year');
    $randomTimestamp = random_int(
        $randomDate->setTime($open[0], $open[1], $open[2])->getTimestamp(),
        (clone $randomDate)->setTime($close[0], $close[1], $close[2])->getTimestamp()
    );
    $start = Carbon::createFromTimestamp($randomTimestamp)->startOfHour();
    $end = $start->copy()->add($faker->randomElement(['20 minutes', '40 minutes', '1 hour', '80 minutes', '2 hours']));
    $close = $end->copy()->setTimeFromTimeString($doc->clinic->close);
    if ($end->isAfter($close)) {
        $diff = $start->diffInMinutes($end);
        $end->setTimeFrom($close);
        $start = $end->copy()->subMinutes($diff);
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
