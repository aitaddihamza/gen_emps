<?php

namespace Database\Seeders;

use App\Models\Professeur;
use App\Models\Module;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class ProfesseurSeeder extends Seeder
{
    public function run()
    {
        $professeursData = [
            'Pr.JBAHI' => [
                'nom' => 'Pr.JBAHI',
                'type_prof' => 'vacataire',
                'max_heures' => 20,
                'disponibilites' => [
                    'Mardi' => ['08:30-10:30', '10:40-12:30'],
                    'Jeudi' => ['13:30-15:30', '15:40-17:30'],
                ],
                'modules' => ['SIG et télédiction', 'Radioactivité', 'Analyses électrochimiques et spectrales', 'Topographie'],
            ],
            'Pr.FAIZ' => [
                'nom' => 'Pr.FAIZ',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Anglais'],
            ],
            'Pr.MOUMADI' => [
                'nom' => 'Pr.MOUMADI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Programmation Orienté Objet'],
            ],
            'Pr.ASRAOUI' => [
                'nom' => 'Pr.ASRAOUI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Statistique et probabilité'],
            ],
            'Pr.ERRABIH' => [
                'nom' => 'Pr.ERRABIH',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Droit'],
            ],
            'Pr.KHALFAOUI' => [
                'nom' => 'Pr.KHALFAOUI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Recherche opérationnelle'],
            ],
            'Pr.AMEZIANE' => [
                'nom' => 'Pr.AMEZIANE',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['CAO-DAO'],
            ],
            'Pr.MERZOUQI' => [
                'nom' => 'Pr.MERZOUQI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Bases de données', 'Bases de données Avancées', 'Réalisation', 'Gestion de projet', 'Bases de Traitement d\'images médicales', 'Mini projet de Traitement d\'image'],
            ],
            'Pr.BETTASS' => [
                'nom' => 'Pr.BETTASS',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Résistance des Matériaux', 'Robotiques médicales', 'Biostatistiques'],
            ],
            'Pr.KAHKAHY' => [
                'nom' => 'Pr.KAHKAHY',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Français'],
            ],
            'Pr.BOUHALI' => [
                'nom' => 'Pr.BOUHALI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Français'],
            ],
            'Pr.ANSARI' => [
                'nom' => 'Pr.ANSARI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Changement climatiques', 'Chimie de l\'environnement', 'Géophysique environnementale', 'Microbiologie de l\'environnement', 'Anatomie', 'Gestion de l\'entreprise et Marché public'],
            ],
            'Pr.ERRAZI' => [
                'nom' => 'Pr.ERRAZI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Programmation'],
            ],
            'Pr.ADDI' => [
                'nom' => 'Pr.ADDI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['ESP'],
            ],
            'Pr.BANOUARAB' => [
                'nom' => 'Pr.BANOUARAB',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['ESP'],
            ],
            'Pr.DEBBAGH' => [
                'nom' => 'Pr.DEBBAGH',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Physiologie', 'Biologie'],
            ],
            'Pr.BEHJA' => [
                'nom' => 'Pr.BEHJA',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Economie de santé', 'Comptabilité'],
            ],
            'Pr.SAWALMEH' => [
                'nom' => 'Pr.SAWALMEH',
                'type_prof' => 'vacataire',
                'max_heures' => 20,
                'disponibilites' => [
                    'Mercredi' => ['10:40-12:30', '13:30-15:30'],
                ],
                'modules' => ['Anglais', 'Compétences culturelles et Artistiques'],
            ],
            'Pr.SPAGETTI' => [
                'nom' => 'Pr.SPAGETTI',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Anglais'],
            ],
            'Pr.KHAY' => [
                'nom' => 'Pr.KHAY',
                'type_prof' => 'vacataire',
                'max_heures' => 20,
                'disponibilites' => [
                    'Mercredi' => ['08:30-10:30', '10:40-12:30'],
                    'Vendredi' => ['13:30-15:30', '15:40-17:30'],
                ],
                'modules' => ['Organisation hospitalière'],
            ],
            'Pr.MEFADDEL' => [
                'nom' => 'Pr.MEFADDEL',
                'type_prof' => 'vacataire',
                'max_heures' => 20,
                'disponibilites' => [
                    'Jeudi' => ['08:30-10:30', '10:40-12:30'],
                    'Mercredi' => ['13:30-15:30', '15:40-17:30'],
                ],
                'modules' => ['TOEIC'],
            ],
            'Pr.MOHAMMED' => [
                'nom' => 'Pr.MOHAMMED',
                'type_prof' => 'vacataire',
                'max_heures' => 20,
                'disponibilites' => [
                    'Jeudi' => ['13:30-15:30', '15:40-17:30'],
                    'Vendredi' => ['08:30-10:30', '10:40-12:30'],
                ],
                'modules' => ['Analyse numérique', 'Mathématiques'],
            ],
            'Pr.HOUSBANE' => [
                'nom' => 'Pr.HOUSBANE',
                'type_prof' => 'vacataire',
                'max_heures' => 20,
                'disponibilites' => [
                    'Vendredi' => ['10:40-12:30', '13:30-15:30'],
                    'Mercredi' => ['08:30-10:30', '10:40-12:30'],
                ],
                'modules' => ['Bioinformatique'],
            ],
            'Pr.MOUZOUN' => [
                'nom' => 'Pr.MOUZOUN',
                'type_prof' => 'vacataire',
                'max_heures' => 20,
                'disponibilites' => [
                    'Jeudi' => ['08:30-10:30', '10:40-12:30'],
                    'Vendredi' => ['13:30-15:30', '15:40-17:30'],
                    'Mercredi' => ['13:30-15:30', '15:40-17:30'],
                ],
                'modules' => ['Imagerie Médicale', 'Mini projet de Traitement d\'image', 'Bases de Traitement d\'images médicales', 'Gestion de projet', 'Traitement de signal'],
            ],
            'Pr.EL YANDOUZI' => [
                'nom' => 'Pr.EL YANDOUZI',
                'type_prof' => 'vacataire',
                'max_heures' => 20,
                'disponibilites' => [
                    'Jeudi' => ['10:40-12:30', '13:30-15:30'],
                    'Mardi' => ['13:30-15:30', '15:40-17:30'],
                    'Lundi' => ['08:30-10:30', '10:40-12:30'],
                ],
                'modules' => ['Machine learning', 'Génie Logiciel'],
            ],
            'P7' => [
                'nom' => 'P7',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Sécurité'],
            ],
            'P8' => [
                'nom' => 'P8',
                'type_prof' => 'permanent',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Biologie'],
            ],
            'Pr.ACHIR' => [
                'nom' => 'Pr.ACHIR',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Bases de Traitement d\'images médicales', 'Imagerie Médicale', 'Physiologie', 'Mini projet de Traitement d\'image'],
            ],
            'Pr.AYOUB' => [
                'nom' => 'Pr.AYOUB',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Biologie'],
            ],
            'Pr.PHIRI' => [
                'nom' => 'Pr.PHIRI',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Recherche opérationnelle'],
            ],
            'Pr.GOURAM' => [
                'nom' => 'Pr.GOURAM',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Traitement de signal'],
            ],
            'Pr.SLALMI' => [
                'nom' => 'Pr.SLALMI',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Traitement de signal'],
            ],
            'Pr.EL MOUFID' => [
                'nom' => 'Pr.EL MOUFID',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Robotiques médicales'],
            ],
            'Pr.AKKAOUI' => [
                'nom' => 'Pr.AKKAOUI',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Machine learning', 'Bases de données Avancées'],
            ],
            'D4' => [
                'nom' => 'D4',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Bioinformatique', 'Biologie'],
            ],
            'Pr.ZOUBIR' => [
                'nom' => 'Pr.ZOUBIR',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Analyse numérique', 'Programmation'],
            ],
            'Pr.YANDOUZI' => [
                'nom' => 'Pr.YANDOUZI',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Programmation Orienté Objet', 'Génie Logiciel'],
            ],
            'Pr.JOUICHA' => [
                'nom' => 'Pr.JOUICHA',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['SIG et télédiction'],
            ],
            'Pr.ACHLIOUI' => [
                'nom' => 'Pr.ACHLIOUI',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Résistance des Matériaux'],
            ],
            'Pr.SAQAT' => [
                'nom' => 'Pr.SAQAT',
                'type_prof' => 'doctorant',
                'max_heures' => 20,
                'disponibilites' => [],
                'modules' => ['Microbiologie de l\'environnement', 'Chimie de l\'environnement'],
            ]
        ];

        foreach ($professeursData as $profData) {
            $professeur = Professeur::create([
                'nom' => $profData['nom'],
                'type_prof' => $profData['type_prof'],
                'max_heures' => $profData['max_heures'],
                'disponibilites' => $profData['disponibilites'],
            ]);
            $profName = str_replace('Pr.', '', $profData['nom']);
            $profName = strtolower($profName);
            User::factory()->create([
                // supprimier Pr du nom de prof
                'name' => $profName,
                'email' => $profName . '@suptech.ma',
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // Default password
                'remember_token' => Str::random(10),
                'role' => 'prof',
            ]);

            $professeur->modules()->attach(rand(1, 72));
            // foreach ($profData['modules'] as $moduleNom) {
            //     $module = Module::where('nom', $moduleNom)->first();
            //     $professeur->modules()->attach($module->id);
            // }
        }
    }
}
