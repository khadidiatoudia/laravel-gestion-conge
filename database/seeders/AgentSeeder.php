<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\LieuAffectation;
use Carbon\Carbon;

class AgentSeeder extends Seeder {

    public function run(): void {
        $lieux = LieuAffectation::all();

        if ($lieux->isEmpty()) {
            $this->command->warn('Aucune structure (lieux_affectation) trouvée. Lance le DatabaseSeeder avant.');
            return;
        }

        $agents = [
            ['nom' => 'Diop',    'prenom' => 'Awa',      'sexe' => 'F', 'enfants' => 2, 'anciennete_annees' => 5],
            ['nom' => 'Ndiaye',  'prenom' => 'Moussa',   'sexe' => 'M', 'enfants' => 0, 'anciennete_annees' => 3],
            ['nom' => 'Fall',    'prenom' => 'Fatou',    'sexe' => 'F', 'enfants' => 3, 'anciennete_annees' => 8],
            ['nom' => 'Sarr',    'prenom' => 'Ibrahima', 'sexe' => 'M', 'enfants' => 1, 'anciennete_annees' => 2],
            ['nom' => 'Gueye',   'prenom' => 'Aissatou', 'sexe' => 'F', 'enfants' => 0, 'anciennete_annees' => 1],
            ['nom' => 'Diallo',  'prenom' => 'Cheikh',   'sexe' => 'M', 'enfants' => 2, 'anciennete_annees' => 10],
            ['nom' => 'Ba',      'prenom' => 'Mariama',  'sexe' => 'F', 'enfants' => 4, 'anciennete_annees' => 12],
            ['nom' => 'Sow',     'prenom' => 'Abdou',    'sexe' => 'M', 'enfants' => 0, 'anciennete_annees' => 0],
            ['nom' => 'Cisse',   'prenom' => 'Khady',    'sexe' => 'F', 'enfants' => 1, 'anciennete_annees' => 6],
            ['nom' => 'Faye',    'prenom' => 'Ousmane',  'sexe' => 'M', 'enfants' => 2, 'anciennete_annees' => 4],
        ];

        foreach ($agents as $index => $a) {
            // Répartit les agents entre les différentes structures existantes
            $lieu = $lieux[$index % $lieux->count()];

            Agent::create([
                'nom'                  => $a['nom'],
                'prenom'               => $a['prenom'],
                'matricule_solde'      => 'MAT-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'lieu_affectation_id'  => $lieu->id,
                'date_prise_service'   => Carbon::now()->subYears($a['anciennete_annees'])->subMonths(rand(0, 11)),
                'sexe'                 => $a['sexe'],
                'nombre_enfants'       => $a['enfants'],
                'conges_reportes'      => rand(0, 10),
                'conges_exceptionnels' => 0,
                'actif'                => true,
            ]);
        }

        $this->command->info(count($agents) . ' agents créés avec succès.');
    }
}
