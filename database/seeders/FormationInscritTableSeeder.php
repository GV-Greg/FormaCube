<?php

namespace Database\Seeders;

use App\Model\Formation;
use App\Model\FormationInscrit;
use Illuminate\Database\Seeder;

class FormationInscritTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formations = Formation::all();

        for($i=0; $i < count($formations); $i++) {
            $min = $formations[$i]->min_stagiaires;
            $max = $formations[$i]->max_stagiaires;
            factory(FormationInscrit::class, rand($min, $max))->create([
                'formation_id' => $formations[$i]->id,
            ]);
        }
    }
}
