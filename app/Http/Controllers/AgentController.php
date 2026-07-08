<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\LieuAffectation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller {

    public function index(Request $request) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $query = Agent::with('lieuAffectation');

        // Barre de recherche (Nom, Prénom, Matricule)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('matricule_solde', 'like', "%$search%")
                  ->orWhere('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%");
            });
        }

        // CORRECTION : Filtrage par structure (Code UFR envoyé par le formulaire)
        if ($request->filled('structure')) {
            $query->whereHas('lieuAffectation', function($q) use ($request) {
                $q->where('code', $request->structure);
            });
        }

        $agents = $query->orderBy('nom')->paginate(20)->withQueryString();

        // CORRECTION : On renomme la variable en $structures pour correspondre à la boucle du Blade
        $structures = LieuAffectation::orderBy('code')->get();

        return view('agents.index', compact('agents', 'structures'));
    }

    public function show(Agent $agent) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $agent->load([
            'absences' => fn($q) => $q->orderByDesc('date_debut'),
            'conges' => fn($q) => $q->orderByDesc('date_cessation_service'),
            'lieuAffectation',
            'user'
        ]);
        return view('agents.show', compact('agent'));
    }

    public function attachUser(Request $request, Agent $agent) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();
        if ($user->agent_id && $user->agent_id !== $agent->id) {
            return back()->withErrors(['email' => 'Cet utilisateur est déjà lié à un autre agent.']);
        }

        $user->agent()->associate($agent);
        $user->save();

        return back()->with('success', 'Le compte utilisateur a bien été associé à cet agent.');
    }

    public function detachUser(Agent $agent) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $user = $agent->user;
        if ($user) {
            $user->agent()->dissociate();
            $user->save();
        }

        return back()->with('success', 'Le compte utilisateur a été dissocié de cet agent.');
    }

    public function create() {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $structures = LieuAffectation::orderBy('code')->get();
        return view('agents.create', compact('structures'));
    }

    public function store(Request $request) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        // AJUSTEMENT : Validation stricte incluant le type de personnel (PER/PATS)
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'matricule_solde' => 'required|string|unique:agents,matricule_solde|max:50',
            'lieu_affectation_id' => 'required|exists:lieux_affectation,id', // Nom de table réel utilisé par la migration
            'type_personnel' => 'required|in:PER,PATS', // Ajout indispensable pour l'USSEIN
            'date_prise_service' => 'required|date',
            'sexe' => 'required|in:M,F',
            'nombre_enfants' => 'nullable|integer|min:0',
            'conges_reportes' => 'nullable|integer|min:0',
        ]);

        $validated['nombre_enfants'] = $validated['nombre_enfants'] ?? 0;
        $validated['conges_reportes'] = $validated['conges_reportes'] ?? 0;

        Agent::create($validated);

        return redirect()->route('agents.index')->with('success', 'Agent créé avec succès au sein de l\'institution.');
    }

    public function edit(Agent $agent) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $structures = LieuAffectation::orderBy('code')->get();
        return view('agents.edit', compact('agent', 'structures'));
    }

    public function update(Request $request, Agent $agent) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'matricule_solde' => 'required|string|max:50|unique:agents,matricule_solde,'.$agent->id,
            'lieu_affectation_id' => 'required|exists:lieux_affectation,id',
            'type_personnel' => 'required|in:PER,PATS',
            'date_prise_service' => 'required|date',
            'sexe' => 'required|in:M,F',
            'nombre_enfants' => 'nullable|integer|min:0',
            'conges_reportes' => 'nullable|integer|min:0',
            'conges_exceptionnels' => 'nullable|integer|min:0',
        ]);

        $agent->update($validated);

        return redirect()->route('agents.show', $agent)->with('success', 'Le profil de l\'agent a été mis à jour.');
    }

    public function destroy(Agent $agent) {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $agent->delete();
        return redirect()->route('agents.index')->with('success', 'Agent retiré du système.');
    }
}
