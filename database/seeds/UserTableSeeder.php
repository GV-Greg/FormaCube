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
                'email'=>'admin@interface3namur.be',
                'password'=> bcrypt('m.Mas2022'),
                'fonction'=>'Compte Master',
                'role'=>'master',
                'avatar'=>'man.png',
            ],
            [
                'firstname'=>'Sébastien',
                'lastname'=>'Vanwynsberghe',
                'email'=>'s.vanwynsberghe@interface3namur.be',
                'password'=> bcrypt('s.Van2022'),
                'fonction'=>'Formateur & développeur',
                'role'=>'master',
                'avatar'=>'man.png',
            ],
            [
                'firstname'=>'Frédéric',
                'lastname'=>'Dedobbeleer',
                'email'=>'f.dedobbeleer@interface3namur.be',
                'password'=> bcrypt('f.Ded2022'),
                'fonction'=>'Formateur, Responsable du pôle externe & co-responsable du parc informatique',
                'role'=>'superAdmin',
                'avatar'=>'man.png',
            ],
            [
                'firstname'=>'Christine',
                'lastname'=>'Dereymaeker',
                'email'=>'c.dereymaeker@interface3namur.be	',
                'password'=> bcrypt('c.Der2022'),
                'fonction'=>'Directrice',
                'role'=>'admin',
                'avatar'=>'woman.png',
            ],
            [
                'firstname'=>'Caroline',
                'lastname'=>'Marique',
                'email'=>'c.marique@interface3namur.be	',
                'password'=> bcrypt('c.Mar2022'),
                'fonction'=>'Formatrice, Coordinatrice pédagogique & co-responsable du parc informatique',
                'role'=>'superAdmin',
                'avatar'=>'woman.png',
            ],
            [
                'firstname'=>'Vincianne',
                'lastname'=>'Rouard',
                'email'=>'v.rouard@interface3namur.be	',
                'password'=> bcrypt('v.Rou2022'),
                'fonction'=>'Coordinatrice administrative',
                'role'=>'admin',
                'avatar'=>'woman.png',
            ],
            [
                'firstname'=>'Aline',
                'lastname'=>'Renard',
                'email'=>'a.renard@interface3namur.be	',
                'password'=> bcrypt('a.Ren2022'),
                'fonction'=>'Formatrice & Chargée de communication',
                'role'=>'formateur',
                'avatar'=>'woman.png',
            ],
            [
                'firstname'=>'Alain',
                'lastname'=>'Fustin',
                'email'=>'a.fustin@interface3namur.be	',
                'password'=> bcrypt('a.Fus2022'),
                'fonction'=>'Formateur',
                'role'=>'formateur',
                'avatar'=>'man.png',
            ],
        ];

        foreach ($users as $key => $value) {
            User::create($value);
        }
    }
}
