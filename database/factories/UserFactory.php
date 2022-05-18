<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    $random = $faker->randomElement([0, 1]);
    switch ($random) {
        case 0:
            $firstname = $faker->firstNameMale;
            $lastname = $faker->lastName;
            $avatar = 'man.png';
            break;
        case 1:
            $firstname = $faker->firstNameFemale;
            $lastname = $faker->lastName;
            $avatar = 'woman.png';
            break;
    }
    $role = $faker->randomElement(['formateur', 'formateur', 'formateur', 'formateur', 'admin']);

    return [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => strtolower($firstname) . '.' . strtolower($lastname) . '@' . $faker->safeEmailDomain,
        'password' => bcrypt($faker->unique()->password),
        'fonction' => 'formateur',
        'role' => $role,
        'avatar' => $avatar,
    ];
});
