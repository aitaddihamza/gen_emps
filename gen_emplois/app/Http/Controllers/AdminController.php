<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\Classe;
use Barryvdh\Snappy\Facades\SnappyPdf;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function showTimeTables()
    {
        // Fetch all seances from the database
        $seances = Seance::all();

        // Organize the data into a structured format
        $timetables = [];
        foreach ($seances as $seance) {
            $timetables[$seance->classe][$seance->jour][$seance->creneau][] = [
                'module' => $seance->module_nom,
                'prof' => $seance->professeur_nom,
                'salle' => $seance->salle,
                'semaine_debut' => $seance->semaine_debut,
                'semaine_fin' => $seance->semaine_fin,
            ];
        }

        // Pass the timetables to the view
        return view('admin.timetables', ['timetables' => $timetables]);
    }

    public function export(string $classe)
    {
        // Fetch the timetable for the selected class

        $seances = Seance::where('classe', $classe)->get();

        // Organize the data into a structured format
        $timetable = [];
        foreach ($seances as $seance) {
            $timetable[$seance->jour][$seance->creneau][] = [
                'module' => $seance->module_nom,
                'prof' => $seance->professeur_nom,
                'salle' => $seance->salle,
                'semaine_debut' => $seance->semaine_debut,
                'semaine_fin' => $seance->semaine_fin,
            ];
        }

        // Generate the PDF
        $pdf = SnappyPdf::loadView('pdf.timetable', [
            'classe' => $classe,
            'timetable' => $timetable,
            'logo' => public_path('images/suptech.png'), // Path to the school logo
        ]);

        // Return the PDF as a download
        return $pdf->download("Emploi_du_temps_{$classe}.pdf");
    }
}
