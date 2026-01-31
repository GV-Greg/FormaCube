<?php

namespace Database\Factories;

use App\Model\RecrutementInscrit;
use App\Model\Recrutement;
use App\Model\Inscrit;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecrutementInscritFactory extends Factory
{
    protected $model = RecrutementInscrit::class;

    public function definition(): array
    {
        $recrutement = Recrutement::all()->pluck('id')->toArray();
        $candidat = Inscrit::all()->pluck('id')->toArray();

        return [
            'recrutement_id' => $this->faker->randomElement($recrutement),
            'inscrit_id' => $this->faker->unique()->randomElement($candidat),
            'date_ajout' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = '2 month', $timezone = 'Europe/Paris'),
        ];
    }
}
