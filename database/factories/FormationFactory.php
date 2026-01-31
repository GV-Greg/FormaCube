<?php

namespace Database\Factories;

use App\Model\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationFactory extends Factory
{
    protected $model = Formation::class;

    public function definition(): array
    {
        $number = $this->faker->numberBetween($min = 0, $max = 5);
        $formation = ['Découverte de l\'ordinateur', 'Suite Office', 'Découverte de la programmation', 'Devenir Webdéveloppeur·se', 'Découverte de la data', 'Découverte de l\'intelligence artificielle'];
        $session = ['', 'session 1', 'session 2'];
        $date_debut = $this->faker->dateTimeBetween($startDate = '-3 month', $endDate = '8 month', $timezone = 'Europe/Paris');
        $duree_formation = [10, 40, 60, 120, 160];
        $random_duree = $this->faker->randomElement($duree_formation);
        $interval_formation = '+ '.$random_duree.' days';
        $abreviation = ['DEC ORDI', 'OFFICE', 'DEC PROG', 'WEBDEV', 'DEC DATA', 'DEC IA'];

        return [
            'nom' => $formation[$number],
            'session' => $this->faker->randomElement($session),
            'date_debut' => $date_debut,
            'date_fin' => $this->faker->dateTimeInInterval($startDate = $date_debut, $interval = $interval_formation, $timezone = 'Europe/Paris'),
            'min_stagiaires' => $this->faker->numberBetween($min = 5, $max = 10),
            'max_stagiaires' => $this->faker->numberBetween($min = 10, $max = 15),
            'nbre_heures' => $random_duree * 7,
            'salle_id' => $this->faker->numberBetween($min = 1, $max = 6),
            'statut' => 'en cours',
            'user_id' => $this->faker->numberBetween($min = 3, $max = 12),
            'abreviation' => $abreviation[$number],
            'duree_rgpd' => $this->faker->numberBetween($min = 1, $max = 5),
        ];
    }
}
