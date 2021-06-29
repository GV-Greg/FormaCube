<?php

namespace Database\Seeders;

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
        factory(FormationInscrit::class, 80)->create();

    }
}
