<?php
namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Conge;
use App\Models\User;
use App\Services\CongeCalculator;
use App\Mail\CongeApprouve;
use App\Mail\CongeRefuse;
use App\Mail\NouvelleDemandeConge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CongeController extends Controller {
    public function create(Agent $agent) {
        if (!Auth::user()->isAdmin() && Auth::user()->agent?->id !== $agent->id) {
            abort(403);
        }
        return view('conges.create', compact('agent'));
    }

    public function store(Request $request, Agent $agent) {
        if (!Auth::user()->isAdmin() && Auth::user()->agent?->id !== $agent->id) {
            abort(403);
        }
        $validated = $request->validate([
            'jours_a_prendre' => 'required|integer|min:1',
            'date_cessation_service' => 'required|date',
            'observations' => 'nullable|string|max:500',
            'deductible' => 'nullable|boolean',
        ]);

        $dateCessation = Carbon::parse($validated['date_cessation_service']);
        $annee = $dateCessation->year;
        $joursAPrendre = (int)$validated['jours_a_prendre'];
        $deductible = $request->has('deductible') ? (bool)$request->deductible : true;

        // Vérifier que l'agent a assez de jours (sauf si non déductible)
        if ($deductible && $joursAPrendre > $agent->conges_dus) {
            return back()->withErrors(['jours_a_prendre' => 'Le nombre de jours demandés ('.$joursAPrendre.') dépasse les jours disponibles ('.$agent->conges_dus.').'])->withInput();
        }

        $calculator = new CongeCalculator($annee);
        $dateReprise = $calculator->calculerDateReprise($dateCessation, $joursAPrendre);
        $statut = Auth::user()->isAdmin() ? 'approuve' : 'en_attente';

        $conge = Conge::create([
            'agent_id' => $agent->id,
            'jours_a_prendre' => $joursAPrendre,
            'date_cessation_service' => $dateCessation,
            'date_reprise_service' => $dateReprise,
            'annee' => $annee,
            'statut' => $statut,
            'observations' => $validated['observations'] ?? null,
            'deductible' => $deductible,
        ]);

        // Si la demande est en attente (soumise par l'agent lui-même), on notifie
        // les admins et gestionnaires RH pour qu'ils puissent la valider.
        if ($statut === 'en_attente') {
            $conge->load('agent.lieuAffectation');
            $destinataires = User::whereIn('role', ['admin', 'gestionnaire'])
                ->whereNotNull('email')
                ->get();

            foreach ($destinataires as $destinataire) {
                Mail::to($destinataire->email)->send(new NouvelleDemandeConge($conge));
            }
        }

        $redirectRoute = Auth::user()->isAdmin() ? route('agents.show', $agent) : route('user.dashboard');
        return redirect($redirectRoute)->with('success', 'Congé enregistré. Date de reprise calculée : ' . $dateReprise->format('d/m/Y'));
    }

    public function updateStatut(Request $request, Conge $conge) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate(['statut' => 'required|in:en_attente,approuve,termine,annule']);
        $oldStatut = $conge->statut;
        $conge->update(['statut' => $request->statut]);

        // Charger l'agent et l'utilisateur associé
        $conge->load('agent.user');
        $user = $conge->agent->user;

        // Envoyer un email si le statut change vers approuvé ou annulé, et qu'il y a un utilisateur
        if ($user && $oldStatut !== $request->statut) {
            if ($request->statut === 'approuve') {
                Mail::to($user->email)->send(new CongeApprouve($conge));
            } elseif ($request->statut === 'annule') {
                Mail::to($user->email)->send(new CongeRefuse($conge));
            }
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Conge $conge) {
        $agent = $conge->agent;
        $conge->delete();
        return redirect()->route('agents.show', $agent)->with('success', 'Congé supprimé.');
    }
}
