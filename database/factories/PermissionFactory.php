<?php

$factory->define(App\Models\Permission::class, function (Faker\Generator $faker) {
    return [
        "title" => $faker->name,
    ];
});
