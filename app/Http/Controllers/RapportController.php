<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LieuAffectation;
use Barryvdh\DomPDF\Facade\Pdf;

class RapportController extends Controller {

    /**
     * Page d'accueil du module Rapports : liste des structures réellement
     * enregistrées en base (au lieu d'une liste codée en dur).
     */
    public function index() {
        $structures = LieuAffectation::orderBy('nom')->get();

        // Groupes réglementaires du personnel d'université
        $corpsPersonnel = [
            (object) ['code' => 'PER',  'nom' => 'Personnel Enseignant et de Recherche (PER)'],
            (object) ['code' => 'PATS', 'nom' => 'Personnel Admin., Technique et de Service (PATS)'],
        ];

        return view('rapports.index', compact('structures', 'corpsPersonnel'));
    }

    /**
     * Affiche le tableau HTML des congés pour une structure donnée.
     */
    public function generer(Request $request, LieuAffectation $lieu) {
        $annee = (int) $request->query('annee', now()->year);

        $agents = $lieu->agents()
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        return view('rapports.tableau', compact('lieu', 'agents', 'annee'));
    }

    /**
     * Exporte en PDF le rapport d'une structure donnée.
     */
    public function exportPdf(Request $request, LieuAffectation $lieu) {
        $annee = (int) $request->query('annee', now()->year);

        $agents = $lieu->agents()
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        $pdf = Pdf::loadView('rapports.pdf', compact('lieu', 'agents', 'annee'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('rapport_conges_' . $lieu->code . '_' . $annee . '.pdf');
    }

    /**
     * Exporte en PDF le rapport global (toutes les structures).
     */
    public function exportAll(Request $request) {
        $annee = (int) $request->query('annee', now()->year);

        $lieux = LieuAffectation::with(['agents' => function ($query) {
                $query->where('actif', true)->orderBy('nom');
            }])
            ->orderBy('nom')
            ->get();

        $pdf = Pdf::loadView('rapports.pdf_all', compact('lieux', 'annee'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rapport_global_conges_' . $annee . '.pdf');
    }

    /**
     * Exporte en CSV (compatible Excel) le rapport d'une structure donnée.
     */
    public function exportCsv(Request $request, LieuAffectation $lieu) {
        $annee = (int) $request->query('annee', now()->year);

        $agents = $lieu->agents()
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        $filename = 'rapport_conges_' . $lieu->code . '_' . $annee . '.csv';

        return $this->streamCsv($filename, $agents);
    }

    /**
     * Exporte en CSV (compatible Excel) le rapport global (toutes structures).
     */
    public function exportAllCsv(Request $request) {
        $annee = (int) $request->query('annee', now()->year);

        $lieux = LieuAffectation::with(['agents' => function ($query) {
                $query->where('actif', true)->orderBy('nom');
            }])
            ->orderBy('nom')
            ->get();

        $agents = $lieux->flatMap(function ($lieu) {
            return $lieu->agents->map(function ($agent) use ($lieu) {
                $agent->setAttribute('structure_nom', $lieu->nom);
                $agent->setAttribute('structure_code', $lieu->code);
                return $agent;
            });
        });

        $filename = 'rapport_global_conges_' . $annee . '.csv';

        return $this->streamCsv($filename, $agents, includeStructure: true);
    }

    /**
     * Génère et streame un fichier CSV compatible Excel à partir d'une
     * collection d'agents (avec cumul, séparateur ";" pour Excel FR/SN,
     * et BOM UTF-8 pour un affichage correct des accents).
     */
    private function streamCsv(string $filename, $agents, bool $includeStructure = false) {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($agents, $includeStructure) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour qu'Excel affiche correctement les accents
            fwrite($handle, "\xEF\xBB\xBF");

            $columns = [];
            if ($includeStructure) {
                $columns[] = 'Structure';
            }
            $columns = array_merge($columns, [
                'Nom et Prénom', 'Matricule', 'Congés Dus (j)', 'À Prendre (j)',
                'Absences (j)', 'Restants (j)', 'Date Cessation', 'Date Reprise',
            ]);
            fputcsv($handle, $columns, ';');

            foreach ($agents as $agent) {
                $row = [];
                if ($includeStructure) {
                    $row[] = $agent->structure_code . ' - ' . $agent->structure_nom;
                }
                $row = array_merge($row, [
                    $agent->nom_complet,
                    $agent->matricule_solde,
                    $agent->conges_dus,
                    $agent->jours_a_prendre,
                    $agent->absences_deductibles_annee,
                    $agent->jours_restants,
                    $agent->date_cessation ? \Carbon\Carbon::parse($agent->date_cessation)->format('d/m/Y') : '-',
                    $agent->date_reprise ? \Carbon\Carbon::parse($agent->date_reprise)->format('d/m/Y') : '-',
                ]);
                fputcsv($handle, $row, ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
