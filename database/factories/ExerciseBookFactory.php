<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\ExerciseBook::class, function (Faker $faker) {
    return [
        'name' => $faker->realText($maxNbChars = 10, $indexSize = 1),
        'user_id' => $faker->numberBetween($min = 1, $max = 6),
        'created_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
        'updated_at' => $faker->dateTime($max = 'now', $timezone = date_default_timezone_get()),
    ];
});
