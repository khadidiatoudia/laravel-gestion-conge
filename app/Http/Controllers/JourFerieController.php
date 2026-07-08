<?php

namespace App\Http\Controllers;

use App\Models\JourFerie;
use Illuminate\Http\Request;

class JourFerieController extends Controller
{
    public function index()
    {
        $annee = request('annee', now()->year);

        // 1. Récupérer les jours spécifiques non récurrents de l'année choisie
        $joursSpecifiques = JourFerie::where('annee', $annee)
            ->where('recurrent', false)
            ->get();

        // 2. Récupérer les jours récurrents uniques (évite les doublons)
        $joursRecurrents = JourFerie::where('recurrent', true)
            ->latest()
            ->get()
            ->unique('nom');

        // 3. Fusionner les collections et trier par date
        $jours = $joursSpecifiques->concat($joursRecurrents)->sortBy('date');

        return view('jours_feries.index', compact('jours', 'annee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'date' => 'required|date',
            'annee' => 'required|integer|min:2020|max:2030',
            'recurrent' => 'nullable|boolean',
        ]);

        JourFerie::create($request->only('nom', 'date', 'annee') + [
            'recurrent' => $request->boolean('recurrent')
        ]);

        return back()->with('success', 'Jour férié ajouté.');
    }

    // Le paramètre $jourFerie correspond maintenant parfaitement au {jourFerie} de web.php
    public function destroy(JourFerie $jourFerie)
    {
        $jourFerie->delete();
        return back()->with('success', 'Jour férié supprimé.');
    }
}
