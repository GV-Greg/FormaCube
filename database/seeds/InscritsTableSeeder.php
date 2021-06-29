<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\Inscrit;

class InscritsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Inscrit::class, 300)->create();
    }
}
