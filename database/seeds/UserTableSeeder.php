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
        $user = [
            [
                'firstname'=>'Gregory',
                'lastname'=>'Van den Bergh',
                'email'=>'g.vandenbergh@interface3namur.be',
                'password'=> bcrypt('rootroot'),
                'fonction'=>'Développeur de l\'ERP',
                'role'=>'master',
                'avatar'=>'man.png',
            ],
            [
                'firstname'=>'Admin',
                'lastname'=>'Admin',
                'email'=>'admin@interface3namur.be',
                'password'=> bcrypt('rootroot'),
                'fonction'=>'Compte Admin',
                'role'=>'admin',
                'avatar'=>'man.png',
            ],
            [
                'firstname'=>'SuperAdmin',
                'lastname'=>'SuperAdmin',
                'email'=>'superadmin@interface3namur.be',
                'password'=> bcrypt('rootroot'),
                'fonction'=>'Compte SuperAdmin',
                'role'=>'superAdmin',
                'avatar'=>'man.png',
            ],
            [
                'firstname'=>'Formateur',
                'lastname'=>'Formateur',
                'email'=>'formateur@interface3namur.be',
                'password'=> bcrypt('rootroot'),
                'fonction'=>'Compte Formateur',
                'role'=>'formateur',
                'avatar'=>'man.png',
            ],
            [
                'firstname'=>'Test',
                'lastname'=>'Exemple',
                'email'=>'contact@interface3namur.be',
                'password'=> bcrypt('contact-if3n'),
                'fonction'=>'Compte exemple pour test',
                'role'=>'admin',
                'avatar'=>'woman.png',
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
