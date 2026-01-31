<?php

namespace Database\Factories;

use App\Model\FormationInscrit;
use App\Model\Formation;
use App\Model\Inscrit;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationInscritFactory extends Factory
{
    protected $model = FormationInscrit::class;

    public function definition(): array
    {
        $formations = Formation::all()->pluck('id')->toArray();
        $stagiaires = Inscrit::all()->pluck('id')->toArray();

        return [
            'formation_id' => $this->faker->randomElement($formations),
            'inscrit_id' => $this->faker->unique()->randomElement($stagiaires),
            'date_ajout' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = '2 month', $timezone = 'Europe/Paris'),
        ];
    }
}
