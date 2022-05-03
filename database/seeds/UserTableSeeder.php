<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'firstname'=>'Master',
                'lastname'=>'Master',
                'email'=>'master@creacube.be',
                'password'=> bcrypt('m.Mas2022'),
                'fonction'=>'Compte Master',
                'role'=>'master',
                'avatar'=>'man.png',
            ],
            [
                'firstname'=>'Demo',
                'lastname'=>'Demo',
                'email'=>'demo@creacube.be',
                'password'=> bcrypt('demo.2022'),
                'fonction'=>'Compte Démo',
                'role'=>'admin',
                'avatar'=>'man.png',
            ],
        ];

        foreach ($users as $key => $value) {
            User::create($value);
        }
    }
}
