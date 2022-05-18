<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Inscrit;
use App\Model\Villes;
use Faker\Generator as Faker;

$factory->define(Inscrit::class, function (Faker $faker) {
    $genres = ['homme', 'femme', '3e genre'];
    $random = $faker->randomElement([0, 1, 2]);
    switch ($random) {
        case 0:
            $prenom = $faker->firstNameMale;
            $nom = $faker->lastName;
            break;
        case 1:
            $prenom = $faker->firstNameFemale;
            $nom = $faker->lastName;
            break;
        case 2:
            $prenom = $faker->firstName;
            $nom = $faker->lastName;
            break;
    }
    $villes = Villes::all()->pluck('id')->toArray();

    return [
        'nom' => $nom,
        'prenom' => $prenom,
        'genre' => $genres[$random],
        'date_naissance' => $faker->date($format = 'Y-m-d', $max = '-18 years'),
        'rue' => $faker->streetName,
        'numero' => $faker->buildingNumber,
        'ville_id' => $faker->randomElement($villes),
        'email' => strtolower($prenom) . '.' . strtolower($nom) . '@' . $faker->safeEmailDomain,
        'tel' => $faker->phoneNumber,
        'prospect' => $faker->boolean,
    ];
});
