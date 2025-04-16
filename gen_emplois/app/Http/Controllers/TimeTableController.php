<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professeur;
use App\Models\Salle;
use App\Models\Classe;
use App\Models\Module;
use App\Models\Seance;
use App\Models\Creneau; // Modèle pour les créneaux horaires
use App\Models\Jour; // Modèle pour les jours
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimeTableController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'weeks' => 'required|integer|min:1',
        ]);

        try {
            $data = $this->prepareData($request->input('weeks'));
            $response = Http::timeout(600)->post(config('services.python_api.url'), $data);

            if ($response->successful()) {
                $timetables = $response->json();

                $this->storeData($timetables["timetables"]);

                return response()->json([
                    'success' => true,
                    'message' => 'Les emplois du temps ont été générés avec succès.',
                    'timetables' => $timetables["timetables"],
                    'analysis' => $timetables["analysis"] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la génération des emplois du temps.',
                ], 500);
            }
        } catch (\Exception $e) {
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

    public function storeData($timetables)
    {
        DB::table('seances')->truncate();
        DB::beginTransaction();

        try {
            Log::info('Début de la sauvegarde des emplois du temps.');

            // Vider la table 'seances' avant d'insérer les nouvelles données
            Log::info('Table "seances" vidée.');

            foreach ($timetables as $classe => $jours) {
                Log::info("Traitement de la classe : $classe");

                foreach ($jours as $jour => $creneaux) {
                    Log::info("Traitement du jour : $jour");

                    foreach ($creneaux as $creneau => $seances) {
                        Log::info("Traitement du créneau : $creneau");

                        foreach ($seances as $seance) {
                            Log::info("Traitement de la séance : " . json_encode($seance));

                            Seance::create([
                                'module_nom' => $seance["module"],
                                'salle' => $seance["salle"],
                                'creneau' => $creneau,
                                'jour' => $jour,
                                'classe' => $classe,
                                'professeur_nom' => $seance["prof"],
                                'semaine_debut' => $seance['semaine_debut'],
                                'semaine_fin' => $seance['semaine_fin'],
                            ]);

                            Log::info('Séance créée avec succès.');
                        }
                    }
                }
            }

            DB::commit();
            Log::info('Sauvegarde des emplois du temps terminée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la sauvegarde des emplois du temps : ' . $e->getMessage());
            throw $e;
        }
    }
}
