<?php

$factory->define(App\Models\Document::class, function (Faker\Generator $faker) {
    return [
        "property_id" => factory('App\Models\Property')->create(),
        "user_id" => factory('App\Models\User')->create(),
        "name" => $faker->name,
    ];
});
