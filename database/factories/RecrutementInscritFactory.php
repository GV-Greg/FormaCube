<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\RecrutementInscrit;
use App\Model\Recrutement;
use App\Model\Inscrit;
use Faker\Generator as Faker;

$factory->define(RecrutementInscrit::class, function (Faker $faker) {
    $recrutement = Recrutement::all()->pluck('id')->toArray();
    $candidat = Inscrit::all()->pluck('id')->toArray();

    return [
        'recrutement_id' => $faker->randomElement($recrutement),
        'inscrit_id' => $faker->unique()->randomElement($candidat),
        'date_ajout' => $faker->dateTimeBetween($startDate = 'now', $endDate = '2 month', $timezone = 'Europe/Paris'),
    ];
});
