<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Absence;
use App\Models\Conge;
use App\Models\LieuAffectation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();
        $agent = $user->agent?->load([
            'conges' => fn($q) => $q->orderByDesc('date_cessation_service'),
            'absences' => fn($q) => $q->orderByDesc('date_debut'),
            'lieuAffectation',
        ]);

        if (! $user->isAdmin()) {
            return view('dashboard.user', compact('user', 'agent'));
        }

        $annee = Carbon::now()->year;

        // 1. Structures et Corps officiels de l'USSEIN
        $structuresList = [
            ['code' => 'SAEPAN', 'nom' => "UFR Sciences Agronomiques, d'Élevage, de Pêche-Aquaculture et de Nutrition"],
            ['code' => 'SFI',    'nom' => "UFR Sciences Fondamentales et de l'Ingénieur"],
            ['code' => 'SSE',    'nom' => "UFR Sciences Sociales et Environnementles"],
            ['code' => 'SEJT',   'nom' => "UFR Sciences Économiques, Juridiques et Touristiques"],
            ['code' => 'RECT',   'nom' => "Rectorat et Services Administratifs Centraux"],
        ];

        $corpsPersonnel = [
            ['code' => 'PER',  'nom' => 'Personnel Enseignant et de Recherche'],
            ['code' => 'PATS', 'nom' => 'Personnel Admin., Technique et de Service'],
        ];

        // 2. Compte de l'effectif global réel
        $totalAgents = Agent::count();

        // Répartition par sexe (colonnes existantes dans la migration)
        $totalHommes = Agent::where('sexe', 'M')->count();
        $totalFemmes = Agent::where('sexe', 'F')->count();
        // Garder pour compatibilité avec la vue
        $totalPER  = $totalHommes;
        $totalPATS = $totalFemmes;

        // 3. Suivi des congés et des absences
        $congesEnCours = Conge::where('annee', $annee)
            ->whereDate('date_cessation_service', '<=', now())
            ->whereDate('date_reprise_service', '>=', now())
            ->count();

        $pendingConges = Conge::with('agent.lieuAffectation')
            ->where('statut', 'en_attente')
            ->orderByDesc('date_cessation_service')
            ->get();

        $pendingCount = $pendingConges->count();

        $absencesCeMois = Absence::whereMonth('date_debut', Carbon::now()->month)->count();

        // 4. CORRECTION : Cartographie renommée en $agentsParLieu pour la vue Blade
        $lieux = LieuAffectation::withCount('agents')->get();

        // Regroupement dynamique : on prend tous les lieux et on les regroupe par type
        $agentsParLieu = [];
        foreach ($lieux as $lieu) {
            $agentsParLieu[$lieu->code] = $lieu->agents_count;
        }

        // On prépare aussi une version pour les graphiques avec libellés courts
        $ufrLabels = $lieux->pluck('code')->toArray();
        $ufrValues = $lieux->pluck('agents_count')->toArray();

        // 5. Listes pour les tableaux de bas de page
        $agentsEnConge = Conge::with('agent.lieuAffectation')
            ->whereDate('date_cessation_service', '<=', now())
            ->whereDate('date_reprise_service', '>=', now())
            ->get();

        if (Schema::hasColumn('agents', 'conges_dus')) {
            $agentsCritiques = Agent::with('lieuAffectation')
                ->where('conges_dus', '>=', 48)
                ->orderBy('conges_dus', 'desc')
                ->take(10)
                ->get();
        } else {
            $agentsCritiques = Agent::with('lieuAffectation')->take(5)->get();
        }

        $ufrData = $agentsParLieu;

        return view('dashboard.index', compact(
            'totalAgents', 'totalPER', 'totalPATS', 'totalHommes', 'totalFemmes', 'congesEnCours',
            'pendingConges', 'pendingCount', 'absencesCeMois',
            'ufrData', 'ufrLabels', 'ufrValues',
            'agentsParLieu', 'agentsEnConge', 'agentsCritiques',
            'annee', 'structuresList', 'corpsPersonnel'
        ));
    }
}
