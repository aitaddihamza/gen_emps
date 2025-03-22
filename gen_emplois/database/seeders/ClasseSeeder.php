<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            ['nom' => '2A_GD', 'effectif' => 30],
            ['nom' => '2A_GB', 'effectif' => 25],
            ['nom' => '1A_GB', 'effectif' => 28],
            ['nom' => '1A_GD', 'effectif' => 32],
            ['nom' => '1A_EE', 'effectif' => 27],
        ];

        Classe::insert($classes);
    }
}
