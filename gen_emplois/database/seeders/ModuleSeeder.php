<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Classe;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run()
    {
        $modulesData = [
            '2 année génie digital et AI en santé' => [
                ['nom' => 'Bases de Traitement d\'images médicales', 'volume_horaire' => 28, 'tp_seances' => 8],
                ['nom' => 'Mini projet de Traitement d\'image', 'volume_horaire' => 28, 'tp_seances' => 8],
                ['nom' => 'Robotiques médicales', 'volume_horaire' => 24, 'tp_seances' => 4],
                ['nom' => 'Gestion de projet', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Réalisation', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Organisation hospitalière', 'volume_horaire' => 56, 'tp_seances' => 0],
                ['nom' => 'Bioinformatique', 'volume_horaire' => 56, 'tp_seances' => 0],
                ['nom' => 'Bases de données Avancées', 'volume_horaire' => 56, 'tp_seances' => 4],
                ['nom' => 'Machine learning', 'volume_horaire' => 48, 'tp_seances' => 3],
                ['nom' => 'Economie de santé', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'ESP', 'volume_horaire' => 48, 'tp_seances' => 0],
                ['nom' => 'Français', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Anglais', 'volume_horaire' => 24, 'tp_seances' => 0],
            ],
            '2 année génie biomédical' => [
                ['nom' => 'Imagerie Médicale', 'volume_horaire' => 28, 'tp_seances' => 4],
                ['nom' => 'Bases de Traitement d\'images médicales', 'volume_horaire' => 28, 'tp_seances' => 4],
                ['nom' => 'Mini projet de Traitement d\'image', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Biostatistiques', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Bioinformatique', 'volume_horaire' => 56, 'tp_seances' => 0],
                ['nom' => 'Gestion de projet', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Réalisation', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Organisation hospitalière', 'volume_horaire' => 56, 'tp_seances' => 0],
                ['nom' => 'Bases de données Avancées', 'volume_horaire' => 56, 'tp_seances' => 2],
                ['nom' => 'Physiologie', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Comptabilité', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'ESP', 'volume_horaire' => 48, 'tp_seances' => 0],
                ['nom' => 'Français', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Anglais', 'volume_horaire' => 24, 'tp_seances' => 0],
            ],
            '1 année génie biomédical' => [
                ['nom' => 'Radioactivité', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Traitement de signal', 'volume_horaire' => 24, 'tp_seances' => 4],
                ['nom' => 'Recherche opérationnelle', 'volume_horaire' => 48, 'tp_seances' => 4],
                ['nom' => 'Bases de données', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Droit', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'TOEIC', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'CAO-DAO', 'volume_horaire' => 12, 'tp_seances' => 0],
                ['nom' => 'Programmation Orienté Objet', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Statistique et probabilité', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Physiologie', 'volume_horaire' => 56, 'tp_seances' => 4],
                ['nom' => 'Gestion de l\'entreprise et Marché public', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Compétences culturelles et Artistiques', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'ESP', 'volume_horaire' => 48, 'tp_seances' => 0],
                ['nom' => 'Français', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Anglais', 'volume_horaire' => 24, 'tp_seances' => 0],
            ],
            '1 année génie digital et AI en santé' => [
                ['nom' => 'Radioactivité', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Traitement de signal', 'volume_horaire' => 24, 'tp_seances' => 8],
                ['nom' => 'Recherche opérationnelle', 'volume_horaire' => 48, 'tp_seances' => 4],
                ['nom' => 'Bases de données', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Droit', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'TOEIC', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Programmation Orienté Objet', 'volume_horaire' => 56, 'tp_seances' => 0],
                ['nom' => 'Statistique et probabilité', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Physiologie', 'volume_horaire' => 56, 'tp_seances' => 4],
                ['nom' => 'Gestion de l\'entreprise et Marché public', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Compétences culturelles et Artistiques', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'ESP', 'volume_horaire' => 48, 'tp_seances' => 0],
                ['nom' => 'Français', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Anglais', 'volume_horaire' => 24, 'tp_seances' => 0],
            ],
            '1 année génie environnement' => [
                ['nom' => 'Microbiologie de l\'environnement', 'volume_horaire' => 28, 'tp_seances' => 4],
                ['nom' => 'Géophysique environnementale', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Changement climatiques', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Chimie de l\'environnement', 'volume_horaire' => 28, 'tp_seances' => 8],
                ['nom' => 'Analyses électrochimiques et spectrales', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Résistance des Matériaux', 'volume_horaire' => 28, 'tp_seances' => 4],
                ['nom' => 'Topographie', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'SIG et télédiction', 'volume_horaire' => 28, 'tp_seances' => 8],
                ['nom' => 'Bases de données', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Statistique et probabilité', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Programmation Orienté Objet', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'Recherche opérationnelle', 'volume_horaire' => 28, 'tp_seances' => 0],
                ['nom' => 'TOEIC', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Compétences culturelles et Artistiques', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'ESP', 'volume_horaire' => 48, 'tp_seances' => 0],
                ['nom' => 'Français', 'volume_horaire' => 24, 'tp_seances' => 0],
                ['nom' => 'Anglais', 'volume_horaire' => 24, 'tp_seances' => 0],
            ],
        ];

        foreach ($modulesData as $classeNom => $modules) {
            $classe = Classe::where('nom', $classeNom)->first();

            if (!$classe) {
                echo "Classe not found: $classeNom\n";
                continue; // Skip if no matching class
            }

            foreach ($modules as $moduleData) {
                $module = Module::create($moduleData);
                $classe->modules()->attach($module->id);
                $classe->modules()->attach($module->id);
            }
        }
    }
}
