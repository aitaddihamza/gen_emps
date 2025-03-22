<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professeur;
use App\Models\Salle;
use App\Models\Classe;
use App\Models\Module;
use Illuminate\Support\Facades\Http;

class TimeTableController extends Controller
{
    public function generate(Request $request)
    {
        // Valider les données
        $request->validate([
            'weeks' => 'required|integer|min:1',
        ]);

        try {
            // Préparer les données
            $data = $this->prepareData($request->input('weeks'));

            // Envoyer les données à l'API Python
            $response = Http::timeout(30)->post(config('services.python_api.url'), $data);

            // Vérifier la réponse de l'API
            if ($response->successful()) {
                $timetables = $response->json();

                return response()->json([
                    'success' => true,
                    'message' => 'Les emplois du temps ont été générés avec succès.',
                    'timetables' => $timetables,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la génération des emplois du temps.',
                ], 500);
            }
        } catch (\Exception $e) {
            // Gérer les exceptions
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
            ], 500);
        }
    }

    private function prepareData($weeks)
    {
        // Données de base
        $data = [
            'TOTAL_SEMAINES' => $weeks,
        ];

        // Récupérer les salles de cours
        $sallesCours = Salle::where('type_salle', 'cours')->pluck('capacite', 'nom')->toArray();
        $data['SALLES_COURS'] = $sallesCours;

        // Récupérer les salles de TP
        $sallesTP = Salle::where('type_salle', 'tp')->pluck('nom')->toArray();
        $data['SALLES_TP'] = $sallesTP;

        // Récupérer les classes et leur effectif
        $classesEffectif = Classe::pluck('effectif', 'nom')->toArray();
        $data['CLASSES_EFFECTIF'] = $classesEffectif;

        // Récupérer les modules pour chaque classe
        $classes = Classe::with('modules')->get();
        $data['CLASSES'] = [];

        foreach ($classes as $classe) {
            $data['CLASSES'][$classe->nom] = [];

            foreach ($classe->modules as $module) {
                $data['CLASSES'][$classe->nom][$module->nom] = [
                    'volume' => $module->volume_horaire,
                    'tp_seances' => $module->tp_seances,
                ];
            }
        }

        // Récupérer les professeurs et leurs informations
        $professeurs = Professeur::with('modules')->get();
        $data['PROFESSEURS'] = [];

        foreach ($professeurs as $professeur) {
            $data['PROFESSEURS'][$professeur->nom] = [
                'modules' => $professeur->modules->pluck('nom')->toArray(),
                'type' => $professeur->type_prof,
            ];

            if ($professeur->type_prof === 'vacataire') {
                $data['PROFESSEURS'][$professeur->nom]['disponibilites'] = $professeur->disponibilites;
            } else {
                $data['PROFESSEURS'][$professeur->nom]['max_heures'] = $professeur->max_heures;
            }
        }

        return $data;
    }
}
