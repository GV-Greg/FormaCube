<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Recrutement;
use App\Model\Formation;
use Faker\Generator as Faker;

$factory->define(Recrutement::class, function (Faker $faker) {
    $formation = Formation::all()->pluck('id')->toArray();

    return [
        'formation_id' => $faker->randomElement($formation),
        'date' => $faker->dateTimeBetween($startDate = '-3 month', $endDate = 'now', $timezone = 'Europe/Paris'),
    ];
});
