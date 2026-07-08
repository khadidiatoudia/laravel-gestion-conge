<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Conge;
use App\Models\Absence;
use App\Models\LieuAffectation;
use Carbon\Carbon;

class RapportController extends Controller {
    public function index() {
        // Liste réglementaire des UFR pour les filtres de rapports
        $structures = [
            (object) ['code' => 'SAEPAN', 'nom' => 'UFR Sciences Agronomiques, Élevage, Pêche & Nutrition'],
            (object) ['code' => 'SFI',    'nom' => "UFR Sciences Fondamentales et de l'Ingénieur"],
            (object) ['code' => 'SSE',    'nom' => 'UFR Sciences Sociales et Environnementles'],
            (object) ['code' => 'SEJT',   'nom' => 'UFR Sciences Économiques, Juridiques et Touristiques'],
            (object) ['code' => 'RECT',   'nom' => 'Rectorat et Services Administratifs Centralisés'],
        ];

        // Groupes réglementaires du personnel d'université
        $corpsPersonnel = [
            (object) ['code' => 'PER',  'nom' => 'Personnel Enseignant et de Recherche (PER)'],
            (object) ['code' => 'PATS', 'nom' => 'Personnel Admin., Technique et de Service (PATS)'],
        ];

        return view('rapports.index', compact('structures', 'corpsPersonnel'));
    }

    public function generer(Request $request) {
        $typeRapport = $request->input('type_rapport', 'conges');
        $structureCode = $request->input('structure_code', 'all');
        $corpsCode = $request->input('corps_code', 'all');
        $dateDebut = $request->input('date_debut', now()->startOfYear()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->format('Y-m-d'));
        $annee = Carbon::parse($dateDebut)->year;

        // Récupérer les agents basés sur les critères
        $query = Agent::with(['lieuAffectation', 'conges', 'absences']);
        
        if ($structureCode !== 'all') {
            $lieu = LieuAffectation::where('code', $structureCode)->first();
            if ($lieu) {
                $query->where('lieu_affectation_id', $lieu->id);
            }
        }

        $agents = $query->get();

        // Préparer les données du rapport
        $rapport = [
            'type' => $typeRapport,
            'structure_code' => $structureCode,
            'corps_code' => $corpsCode,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'annee' => $annee,
            'agents' => $agents,
            'generated_at' => now(),
        ];

        // Retourner selon le type de rapport
        if ($typeRapport === 'conges') {
            return view('rapports.tableau', [
                'lieu' => (object)['nom' => $structureCode === 'all' ? 'Tous les sites' : $structureCode],
                'annee' => $annee,
                'agents' => $agents,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
            ]);
        } elseif ($typeRapport === 'effectifs') {
            return view('rapports.effectifs', [
                'lieu' => (object)['nom' => $structureCode === 'all' ? 'Tous les sites' : $structureCode],
                'annee' => $annee,
                'agents' => $agents,
            ]);
        } elseif ($typeRapport === 'alertes') {
            $agentsAlerte = $agents->filter(function($agent) use ($annee) {
                $conges = $agent->conges()->where('annee', $annee)->sum('jours_a_prendre');
                return $conges >= 48;
            });
            
            return view('rapports.alertes', [
                'lieu' => (object)['nom' => $structureCode === 'all' ? 'Tous les sites' : $structureCode],
                'annee' => $annee,
                'agents' => $agentsAlerte,
            ]);
        }

        return back()->withError('Type de rapport invalide');
    }

    public function exportPdf(Request $request) {
        $typeRapport = $request->input('type_rapport', 'conges');
        $structureCode = $request->input('structure_code', 'all');
        
        // Récupérer les données du rapport (similaire à generer)
        $query = Agent::with(['lieuAffectation', 'conges', 'absences']);
        
        if ($structureCode !== 'all') {
            $lieu = LieuAffectation::where('code', $structureCode)->first();
            if ($lieu) {
                $query->where('lieu_affectation_id', $lieu->id);
            }
        }

        $agents = $query->get();
        $annee = now()->year;

        // Retourner la vue PDF (utilisant dompdf ou similaire)
        return view('rapports.pdf', [
            'type' => $typeRapport,
            'lieu' => (object)['nom' => $structureCode === 'all' ? 'Tous les sites' : $structureCode],
            'annee' => $annee,
            'agents' => $agents,
        ]);
    }

    public function exportAll(Request $request) {
        // Exporter tous les rapports
        $agents = Agent::with(['lieuAffectation', 'conges', 'absences'])->get();
        $annee = now()->year;

        return view('rapports.pdf_all', [
            'annee' => $annee,
            'agents' => $agents,
        ]);
    }
}
