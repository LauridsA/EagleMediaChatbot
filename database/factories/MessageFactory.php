<?php

$factory->define(App\Message::class, function (Faker\Generator $faker) {
    return [
        "message" => $faker->sentence(rand(5, 10)),
        "delay" => $faker->numberBetween(1, 3),
        "image" => $faker->imageUrl()
    ];
});