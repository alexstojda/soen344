<?php

use Faker\Generator as Faker;
use App\Models\Clinic;

$factory->define(Clinic::class, function (Faker $faker) {
    return [
        'name'      => $faker->words(4, true),
        'address'   => $faker->address,
        'phone'     => $faker->phoneNumber,
        'open'      => $faker->randomElement(['6:00','7:00','8:00','9:00','10:00']),
        'close'     => $faker->randomElement(['17:00','18:00','19:00','20:00','21:00']),
    ];
});
