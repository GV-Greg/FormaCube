<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Formation;

class FormationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Formation::class, 20)->create();
    }
}
