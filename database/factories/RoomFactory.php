<?php

use Faker\Generator as Faker;

$factory->define(App\Room::class, function (Faker $faker) {
    return [
        'number' => $faker->buildingNumber,
        'name' => $faker->words(2, true)
    ];
});
