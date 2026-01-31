<?php

namespace Database\Factories;

use App\Model\Recrutement;
use App\Model\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecrutementFactory extends Factory
{
    protected $model = Recrutement::class;

    public function definition(): array
    {
        $formation = Formation::all()->pluck('id')->toArray();

        return [
            'formation_id' => $this->faker->randomElement($formation),
            'date' => $this->faker->dateTimeBetween($startDate = '-3 month', $endDate = 'now', $timezone = 'Europe/Paris'),
        ];
    }
}
