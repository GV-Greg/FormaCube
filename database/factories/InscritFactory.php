<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Inscrit;
use App\Model\Villes;
use Faker\Generator as Faker;

$factory->define(Inscrit::class, function (Faker $faker) {
    $genres = ['homme', 'femme', '3e genre'];
    $villes = Villes::all()->pluck('id')->toArray();

    return [
        'nom' => $faker->lastName,
        'prenom' => $faker->firstName($gender = 'male'|'female'),
        'genre' => $faker->randomElement($genres),
        'date_naissance' => $faker->date($format = 'Y-m-d', $max = '-18 years'),
        'rue' => $faker->streetName,
        'numero' => $faker->buildingNumber,
        'ville_id' => $faker->randomElement($villes),
        'email' => $faker->unique()->safeEmail,
        'tel' => $faker->phoneNumber,
        'prospect' => $faker->boolean,
    ];
});
