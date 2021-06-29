<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Model\Formation;
use App\Model\PouvSub;
use Faker\Generator as Faker;

$factory->define(Formation::class, function (Faker $faker) {
    $formation = ['TACTIC emploi', 'Découverte des métiers de l\'informatique', 'Découverte des métiers de la data', 'E-Working', 'Deux semaines de découverte de la programmation', 'Devenir Webdéveloppeur·se Back-end Junior', 'Devenir Webdéveloppeur·se Back-end orienté data', 'Découverte des outils numériques pros'];
    $session = ['session 1', '', 'session 2'];
    $date_debut = $faker->dateTimeBetween($startDate = '-3 month', $endDate = '8 month', $timezone = 'Europe/Paris');
    $duree_formation = [10, 40, 60, 120, 160];
    $random_duree = $faker->randomElement($duree_formation);
    $interval_formation = '+ '.$random_duree.' days';
    $pouv_sub = PouvSub::all()->pluck('id')->toArray();
    $formation_choice = $faker->randomElement($formation);

    return [
        'nom' => $formation_choice,
        'session' => $faker->randomElement($session),
        'date_debut' => $date_debut,
        'date_fin' => $faker->dateTimeInInterval($startDate = $date_debut, $interval = $interval_formation, $timezone = 'Europe/Paris'),
        'min_stagiaires' => $faker->numberBetween($min = 1, $max = 6),
        'max_stagiaires' => $faker->numberBetween($min = 6, $max = 12),
        'salle_id' => $faker->numberBetween($min = 1, $max = 6),
        'statut' => 'en cours',
        'user_id' => $faker->numberBetween($min = 1, $max = 5),
        'pouvsub_id' => $faker->randomElement($pouv_sub),
        'abreviation' => substr($formation_choice, 0, 5),
        'duree_rgpd' => $faker->numberBetween($min = 1, $max = 5),
    ];
});
