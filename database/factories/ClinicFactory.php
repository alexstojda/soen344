<?php

use Faker\Generator as Faker;
use App\Models\Clinic;

$factory->define(Clinic::class, function (Faker $faker) {
    return [
        'name'      => $faker->words(4, true),
        'address'   => $faker->address,
        'phone'     => $faker->phoneNumber,
        'open'      => $faker->time(),
        'close'     => $faker->time(),
    ];
});
