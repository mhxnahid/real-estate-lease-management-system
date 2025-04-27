<?php

$factory->define(App\Models\Note::class, function (Faker\Generator $faker) {
    return [
        "property_id" => factory('App\Models\Property')->create(),
        "user_id" => factory('App\Models\User')->create(),
        "note_text" => $faker->name,
    ];
});
