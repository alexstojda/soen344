<?php

use Faker\Generator as Faker;
use App\Models\Room;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'number' => $faker->buildingNumber,
        'name' => $faker->words(2, true),
        'clinic_id' => \App\Models\Clinic::all()->random()->id,
    ];
});
