<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            SalleSeeder::class,
            FormationsTableSeeder::class,
            InscritsTableSeeder::class,
            RecrutementsTableSeeder::class,
            RecrutementInscritTableSeeder::class,
            FormationInscritTableSeeder::class,
        ]);
    }
}
