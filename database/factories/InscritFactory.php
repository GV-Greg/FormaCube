<?php

namespace Database\Factories;

use App\Model\Inscrit;
use App\Model\Villes;
use Illuminate\Database\Eloquent\Factories\Factory;

class InscritFactory extends Factory
{
    protected $model = Inscrit::class;

    public function definition(): array
    {
        $genres = ['homme', 'femme', '3e genre'];
        $random = $this->faker->randomElement([0, 1, 2]);
        switch ($random) {
            case 0:
                $prenom = $this->faker->firstNameMale;
                $nom = $this->faker->lastName;
                break;
            case 1:
                $prenom = $this->faker->firstNameFemale;
                $nom = $this->faker->lastName;
                break;
            case 2:
                $prenom = $this->faker->firstName;
                $nom = $this->faker->lastName;
                break;
        }
        $villes = Villes::all()->pluck('id')->toArray();

        return [
            'nom' => $nom,
            'prenom' => $prenom,
            'genre' => $genres[$random],
            'date_naissance' => $this->faker->date($format = 'Y-m-d', $max = '-18 years'),
            'rue' => $this->faker->streetName,
            'numero' => $this->faker->buildingNumber,
            'ville_id' => $this->faker->randomElement($villes),
            'email' => strtolower($prenom) . '.' . strtolower($nom) . '@' . $this->faker->safeEmailDomain,
            'tel' => $this->faker->phoneNumber,
            'prospect' => $this->faker->boolean,
        ];
    }
}
