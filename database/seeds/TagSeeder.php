<?php

namespace Database\Seeders;

use App\Model\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
          ['tag'=>'Suite office'],
          ['tag'=>'Programmation'],
          ['tag'=>'Webdeveloppement'],
          ['tag'=>'Bases PC'],
          ['tag'=>'IA'],
          ['tag'=>'Big Data'],
          ['tag'=>'Orientation'],
        ];

        foreach($tags as $key => $value) {
            Tag::create($value);
        }
    }
}
