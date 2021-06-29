<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Model\RecrutementInscrit;

class RecrutementInscritTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(RecrutementInscrit::class, 60)->create();
    }
}
