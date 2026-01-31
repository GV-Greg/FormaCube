<?php

namespace Database\Seeders;

use App\Model\Salle;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salles = [
            [
                'nom'=>'salle n°1',
            ],
            [
                'nom'=>'salle n°2',
            ],
            [
                'nom'=>'salle n°3',
            ],
            [
                'nom'=>'salle n°4',
            ],
            [
                'nom'=>'salle n°5',
            ],
            [
                'nom'=>'externe',
            ],
            [
                'nom'=>'en ligne',
            ],
        ];

        foreach ($salles as $key => $value) {
            Salle::create($value);
        }
    }
}
