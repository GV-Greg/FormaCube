<?php

namespace Database\Factories;

use App\Model\FormationInscrit;
use App\Model\Formation;
use App\Model\Inscrit;
use App\Model\PouvsubInfos;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;


$factory->define(FormationInscrit::class, function (Faker $faker) {
    $formations = Formation::all()->pluck('id')->toArray();
    $stagiaires = Inscrit::all()->pluck('id')->toArray();

    return [
        'formation_id' => $faker->randomElement($formations),
        'inscrit_id' => $faker->unique()->randomElement($stagiaires),
        'date_ajout' => $faker->dateTimeBetween($startDate = 'now', $endDate = '2 month', $timezone = 'Europe/Paris'),
    ];
});
