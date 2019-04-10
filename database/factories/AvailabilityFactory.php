<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use App\Models\Availability;
use App\Models\Doctor;

$factory->define(Availability::class, function (Faker $faker) {
    $doc = Doctor::inRandomOrder()->first();
    $randomDate = $faker->dateTimeBetween('last month', 'next year');
    $randomTimestamp = random_int(
        $randomDate->setTime($doc->clinic->openTime[0], $doc->clinic->openTime[1], $doc->clinic->openTime[2])
        ->getTimestamp(),
        (clone $randomDate)->setTime($doc->clinic->closeTime[0], $doc->clinic->closeTime[1], $doc->clinic->closeTime[2])
        ->getTimestamp()
    );
    $start = Carbon::createFromTimestamp($randomTimestamp)->startOfHour();
    $end = $start->copy()->add($faker->randomElement(['20 minutes', '40 minutes', '1 hour', '80 minutes', '2 hours']));
    $available = $faker->boolean(100);
    return [
        'doctor_id' => $doc->id,
        'start' => $start,
        'end' => $end,
        'is_working' => $available,
        'message' => $available ? null : $faker->sentence(8),
    ];
});
