<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Module;
use App\Models\Professeur;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        // Dans votre contrôleur
        $modules = Module::with('professeurs')->with('classes')->latest()->paginate(10);
        return view("modules.index", compact('modules'));
    }

    public function create()
    {

        $module = new Module();
        $classes = Classe::all();
        $professeurs = Professeur::all();
        return view('modules.form', compact('module', 'professeurs', 'classes'));
    }

    // Enregistrer un nouveau module
    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'volume_horaire' => 'required|integer|min:1',
            'tp_seances' => 'required|integer|min:0',
            'professeurs' => 'required|array',
            'professeurs.*' => 'exists:professeurs,id',
            'classes' => 'required|array', // Validation pour les classes
            'classes.*' => 'exists:classes,id', // Vérifier que chaque ID de classe existe
        ]);

        // Créer le module
        $module = Module::create([
            'nom' => $request->nom,
            'volume_horaire' => $request->volume_horaire,
            'tp_seances' => $request->tp_seances,
        ]);

        // Attacher les professeurs sélectionnés au module
        if ($request->has('professeurs')) {
            $module->professeurs()->attach($request->professeurs);
        }

        // Attacher les classes sélectionnées au module
        if ($request->has('classes')) {
            $module->classes()->attach($request->classes);
        }

        // Rediriger avec un message de succès
        return redirect()->route('modules.index')
            ->with('success', 'Module créé avec succès.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Module $module)
    {
        $classes = Classe::all();
        $professeurs = Professeur::all();
        return view('modules.form', compact('module', 'professeurs', 'classes'));
    }


    // Mettre à jour un module existant
    public function update(Request $request, Module $module)
    {
        // Valider les données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'volume_horaire' => 'required|integer|min:1',
            'tp_seances' => 'required|integer|min:0',
            'professeurs' => 'nullable|array',
            'professeurs.*' => 'exists:professeurs,id',
            'classes' => 'required|array', // Validation pour les classes
            'classes.*' => 'exists:classes,id', // Vérifier que chaque ID de classe existe
        ]);

        // Mettre à jour les informations du module
        $module->update([
            'nom' => $request->nom,
            'volume_horaire' => $request->volume_horaire,
            'tp_seances' => $request->tp_seances,
        ]);

        // Synchroniser les professeurs affectés au module
        if ($request->has('professeurs')) {
            $module->professeurs()->sync($request->professeurs);
        } else {
            $module->professeurs()->detach();
        }

        // Synchroniser les classes affectées au module
        if ($request->has('classes')) {
            $module->classes()->sync($request->classes);
        } else {
            $module->classes()->detach();
        }

        // Rediriger avec un message de succès
        return redirect()->route('modules.index')
            ->with('info', 'Module mis à jour avec succès.');
    }

    public function destroy(Module $module)
    {
        $mdoule->delete();
        return redirect()->route('modules.index')
            ->with('warning', 'Module supprimé avec succès.');
    }
}
