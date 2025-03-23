<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            ['nom' => '2 année génie digital et AI en santé', 'effectif' => 30],
            ['nom' => '2 année génie biomédical', 'effectif' => 25],
            ['nom' => '1 année génie environnement', 'effectif' => 28],
            ['nom' => '1 année génie biomédical', 'effectif' => 32],
            ['nom' => '1 année génie digital et AI en santé', 'effectif' => 27],
        ];

        Classe::insert($classes);
    }
}
