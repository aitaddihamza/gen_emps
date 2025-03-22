<?php

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    public function run()
    {
        // Salles de cours
        $sallesCours = [
            ['nom' => 'salle 1', 'capacite' => 40, 'type_salle' => 'cours'],
            ['nom' => 'salle 2', 'capacite' => 40, 'type_salle' => 'cours'],
            ['nom' => 'salle 3', 'capacite' => 40, 'type_salle' => 'cours'],
            ['nom' => 'salle 4', 'capacite' => 40, 'type_salle' => 'cours'],
            ['nom' => 'salle 5', 'capacite' => 40, 'type_salle' => 'cours'],
        ];

        // Salles de TP
        $sallesTP = [
            ['nom' => 'TP1', 'capacite' => 20, 'type_salle' => 'tp'],
            ['nom' => 'TP2', 'capacite' => 20, 'type_salle' => 'tp'],
            ['nom' => 'TP3', 'capacite' => 20, 'type_salle' => 'tp'],
        ];

        // Insérer les salles dans la base de données
        Salle::insert(array_merge($sallesCours, $sallesTP));
    }
}
