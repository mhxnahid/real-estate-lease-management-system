<?php

$factory->define(App\Models\Property::class, function (Faker\Generator $faker) {
    return [
        "name" => $faker->name,
        "address" => $faker->name,
    ];
});
