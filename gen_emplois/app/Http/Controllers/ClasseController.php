<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::latest()->paginate(20);
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $classe = new Classe();
        return view('classes.form', compact('classe'));
    }

    // Enregistrer une nouvelle classe
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'effectif' => 'required|integer|min:1',
        ]);

        // Créer la classe
        Classe::create([
            'nom' => $request->nom,
            'effectif' => $request->effectif,
        ]);

        // Rediriger avec un message de succès
        return redirect()->route('classes.index')
            ->with('success', 'Classe créée avec succès.');
    }

    public function edit(string $id)
    {
        $classe = Classe::findOrFail($id);
        return view('classes.form', compact('classe'));
    }

    public function update(Request $request, string $id)
    {


        // Valider les données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'effectif' => 'required|integer|min:1',
        ]);

        $classe = Classe::findOrFail($id);
        // Mettre à jour la classe
        $classe->update([
            'nom' => $request->nom,
            'effectif' => $request->effectif,
        ]);

        // Rediriger avec un message de succès
        return redirect()->route('classes.index')
            ->with('info', 'Classe mise à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $classe = Classe::findOrFail($id);
        $classe->delete();
        return redirect()->route('classes.index')->with('warning', 'Classe supprimé avec succès.');
    }
}
