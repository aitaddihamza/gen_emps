<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salle;

class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::latest()->paginate(20);
        return view("salles.index", compact('salles'));
    }

    public function create()
    {
        $salle = new Salle();
        return view("salles.form", compact('salle'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'type_salle' => 'required|string|in:Amphithéâtre,Salle de cours,Laboratoire,Salle de réunion',
        ]);

        Salle::create($request->only('nom', 'capacite', 'type_salle'));

        return redirect()->route('salles.index')
            ->with('success', 'Salle créée avec succès.');
    }

    public function edit(string $id)
    {
        $salle = Salle::findOrFail($id);
        return view("salles.form", compact("salle"));
    }

    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'type_salle' => 'required|string|in:Amphithéâtre,Salle de cours,Laboratoire,Salle de réunion',
        ]);

        $salle->update($request->only('nom', 'capacite', 'type_salle'));

        return redirect()->route('salles.index')
            ->with('info', 'Salle mise à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $salle = Salle::findOrFail($id);
        $salle->delete();
        return redirect()->route('salles.index')
            ->with('warning', 'Salle supprimé avec succès.');
    }
}
