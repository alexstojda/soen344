<?php

use Faker\Generator as Faker;

$factory->define(App\Availability::class, function (Faker $faker) {
    $start = $faker->dateTimeThisMonth();
    $end = (clone $start)
        ->add(date_interval_create_from_date_string(
            $faker->randomElement(['20 minutes','1 hour', '4 hours', '8 hours'])
        ));
    $available = $faker->boolean(60);
    return [
        'doctor_id' => $faker->randomElement(\App\Doctor::all()->pluck('id')->toArray()),
        'start' => $start,
        'end' => $end,
        'is_available' => $available,
        'reason_of_unavailability' => $available ? null : $faker->sentence(8),
    ];
});
