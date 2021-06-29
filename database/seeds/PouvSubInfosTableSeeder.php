<?php

namespace Database\Seeders;

use App\Model\Formation;
use Illuminate\Database\Seeder;
use App\Model\PouvSubInfos;
use Faker\Factory as Faker;

class PouvSubInfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('fr_BE');
        $formations = Formation::all();
        $boolean = [true, false];

        for($i=0; $i < count($formations); $i++) {
            $datas = [
                'formation_id'=> $formations[$i]['id'],
                'pouvsub_id'=> $formations[$i]['pouvsub_id'],
                'adresse'=> $faker->randomElement($boolean),
                'date_naissance'=> $faker->randomElement($boolean),
                'age'=> $faker->randomElement($boolean),
                'email'=> $faker->randomElement($boolean),
                'num_national'=> $faker->randomElement($boolean),
                'statut_legal'=> $faker->randomElement($boolean),
                'diplome'=> $faker->randomElement($boolean),
                'duree_chomage'=> $faker->randomElement($boolean),
                'source_info'=> $faker->randomElement($boolean),
                'groupe_social'=> $faker->randomElement($boolean),
            ];

            PouvSubInfos::create($datas);
        }
    }
}
