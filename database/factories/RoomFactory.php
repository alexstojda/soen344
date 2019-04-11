<?php

use Faker\Generator as Faker;
use App\Models\Room;
use App\Models\Clinic;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'number' => $faker->buildingNumber,
        'name' => $faker->words(2, true),
        'clinic_id' => Clinic::all()->random()->id,
    ];
});
