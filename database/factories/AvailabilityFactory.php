<?php

use Faker\Generator as Faker;

$factory->define(App\Availability::class, function (Faker $faker) {
    $randomDate = $faker->dateTimeBetween('last month', 'next year');
    $randomTimestamp = random_int($randomDate->setTime(8,0)->getTimestamp(),
        (clone $randomDate)->setTime(20,0)->getTimestamp());
    $start = (new DateTime())->setTimestamp($randomTimestamp);
    $end = (clone $start)
        ->add(date_interval_create_from_date_string(
            $faker->randomElement(['20min','40min', '1hour', '2hours'])
        ));
    $available = $faker->boolean(60);
    return [
        'doctor_id' => \App\Doctor::all()->random(),
        'start' => $start,
        'end' => $end,
        'is_available' => $available,
        'reason_of_unavailability' => $available ? null : $faker->sentence(8),
    ];
});
