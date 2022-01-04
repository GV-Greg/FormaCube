<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\PouvSub;

class PouvSubTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pouvsubs = [
            [
                'nom'=>'Interface3',
                'adresse'=> false,
                'date_naissance'=> false,
                'age'=> false,
                'email'=> true,
                'num_national'=> false,
                'statut_legal'=> false,
                'diplome'=> false,
                'duree_chomage'=> false,
                'source_info'=> false,
                'groupe_social'=> false,
            ],
            [
                'nom'=>'SPW',
                'adresse'=> true,
                'date_naissance'=> true,
                'age'=> false,
                'email'=> false,
                'num_national'=> true,
                'statut_legal'=> true,
                'diplome'=> true,
                'duree_chomage'=> true,
                'source_info'=> false,
                'groupe_social'=> false,
            ],
            [
                'nom'=>'Cefora',
                'adresse'=> true,
                'date_naissance'=> true,
                'age'=> true,
                'email'=> true,
                'num_national'=> true,
                'statut_legal'=> false,
                'diplome'=> true,
                'duree_chomage'=> true,
                'source_info'=> true,
                'groupe_social'=> false,
            ],
            [
                'nom'=>'DBSF',
                'adresse'=> true,
                'date_naissance'=> false,
                'age'=> true,
                'email'=> false,
                'num_national'=> false,
                'statut_legal'=> false,
                'diplome'=> false,
                'duree_chomage'=> false,
                'source_info'=> false,
                'groupe_social'=> true,
            ],
            [
                'nom'=>'Forem',
                'adresse'=> true,
                'date_naissance'=> false,
                'age'=> false,
                'email'=> false,
                'num_national'=> true,
                'statut_legal'=> false,
                'diplome'=> false,
                'duree_chomage'=> false,
                'source_info'=> false,
                'groupe_social'=> false,
            ],
            [
                'nom'=>'ING',
                'adresse'=> false,
                'date_naissance'=> false,
                'age'=> false,
                'email'=> false,
                'num_national'=> false,
                'statut_legal'=> false,
                'diplome'=> false,
                'duree_chomage'=> false,
                'source_info'=> false,
                'groupe_social'=> false,
            ],
        ];

        foreach ($pouvsubs as $key => $value) {
            PouvSub::create($value);
        }
    }
}
