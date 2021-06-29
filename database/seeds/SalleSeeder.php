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
                'nom'=>'salle blue',
            ],
            [
                'nom'=>'grande salle',
            ],
            [
                'nom'=>'salle rose',
            ],
            [
                'nom'=>'salle PC',
            ],
            [
                'nom'=>'salle externe',
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
