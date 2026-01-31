<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Recrutement;

class RecrutementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Recrutement::class, 10)->create();
    }
}
