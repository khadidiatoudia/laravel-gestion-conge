<?php
namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Absence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsenceController extends Controller {
    public function create(Agent $agent) {
        return view('absences.create', compact('agent'));
    }

    public function store(Request $request, Agent $agent) {
        $validated = $request->validate([
            'date_debut' => 'required|date',
            'nombre_jours' => 'required|integer|min:1',
            'type' => 'required|in:ordinaire,exceptionnel',
            'motif_exceptionnel' => 'nullable|required_if:type,exceptionnel|in:mariage,bapteme,deces_pere,deces_mere,deces_epouse,deces_enfant',
            'motif' => 'nullable|string|max:500',
        ]);

        $dateDebut = Carbon::parse($validated['date_debut']);
        $dateFin = $dateDebut->copy()->addDays($validated['nombre_jours'] - 1);
        $deductible = $validated['type'] === 'ordinaire';

        Absence::create([
            'agent_id' => $agent->id,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nombre_jours' => $validated['nombre_jours'],
            'type' => $validated['type'],
            'motif_exceptionnel' => $validated['motif_exceptionnel'] ?? null,
            'motif' => $validated['motif'] ?? null,
            'annee' => $dateDebut->year,
            'deductible' => $deductible,
        ]);

        return redirect()->route('agents.show', $agent)->with('success', 'Absence enregistrée. Les jours de congé ont été mis à jour automatiquement.');
    }

    public function destroy(Absence $absence) {
        $agent = $absence->agent;
        $absence->delete();
        return redirect()->route('agents.show', $agent)->with('success', 'Absence supprimée.');
    }
}
