<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professeur;

class ProfesseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $professeurs = Professeur::latest()->paginate(10);
        return view('professeurs.index', compact('professeurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $professeur = new Professeur();
        return view('professeurs.form', compact('professeur'));
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'type_prof' => 'required|in:PERMANENT,VACATAIRE,DOCTORANT',
            'max_heures' => 'nullable|integer',
            'disponibilites' => 'nullable|array',
            'disponibilites.*' => 'array',
            'disponibilites.*.*' => 'string|in:08:30-10:30,10:40-12:30,13:30-15:30,15:40-17:30',
        ]);

        // Création du professeur
        $professeur = Professeur::create([
            'nom' => $request->nom,
            'type_prof' => $request->type_prof,
            'max_heures' => $request->max_heures ?? 20,
        ]);

        // Si le professeur est vacataire, enregistrer ses disponibilités
        if ($request->type_prof === 'VACATAIRE') {
            $professeur->disponibilites = $request->disponibilites;
            $professeur->save();
        }

        // Redirection avec un message de succès
        return redirect()->route('professeurs.index')->with('success', 'Professeur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Professeur $professeur)
    {
        return view('professeurs.form', compact('professeur'));
    }

    public function update(Request $request, Professeur $professeur)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'type_prof' => 'required|in:PERMANENT,VACATAIRE,DOCTORANT',
            'max_heures' => 'nullable|integer',
            'disponibilites.*' => 'array',
            'disponibilites.*.*' => 'string|in:08:30-10:30,10:40-12:30,13:30-15:30,15:40-17:30',
        ]);

        // Mise à jour des informations de base du professeur
        $professeur->update([
            'nom' => $request->nom,
            'type_prof' => $request->type_prof,
            'max_heures' => $request->max_heures ?? 20,
        ]);

        // Si le professeur est vacataire, enregistrer ses disponibilités
        if ($request->type_prof === 'VACATAIRE') {
            $professeur->disponibilites = $request->disponibilites;
            $professeur->save();
        }

        // Redirection avec un message de succès
        return redirect()->route('professeurs.index')->with('info', 'Professeur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professeur $professeur)
    {

        $professeur->delete();
        return redirect()->route('professeurs.index')->with('warning', 'Professeur supprimé avec succès.');
    }
}
