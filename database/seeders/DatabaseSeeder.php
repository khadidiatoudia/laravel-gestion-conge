<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LieuAffectation;
use App\Models\JourFerie;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@universite.sn',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Gestionnaire RH',
            'email' => 'rh@universite.sn',
            'password' => Hash::make('password'),
            'role' => 'gestionnaire',
        ]);

        $lieux = [
            ['nom' => 'Direction des Ressources Humaines', 'type' => 'direction', 'code' => 'DRH'],
            ['nom' => 'Direction Administrative et Financière', 'type' => 'direction', 'code' => 'DAF'],
            ['nom' => 'Direction des Affaires Académiques', 'type' => 'direction', 'code' => 'DAA'],
            ['nom' => "UFR Sciences Agronomiques, d'Élevage, de Pêche-Aquaculture et de Nutrition", 'type' => 'ufr', 'code' => 'SAEPAN'],
            ['nom' => "UFR Sciences Fondamentales et de l'Ingénieur", 'type' => 'ufr', 'code' => 'SFI'],
            ['nom' => 'UFR Sciences Sociales et Environnementales', 'type' => 'ufr', 'code' => 'SSE'],
            ['nom' => 'UFR Sciences Économiques, Juridiques et Touristiques', 'type' => 'ufr', 'code' => 'SEJT'],
            ['nom' => 'Rectorat (Cabinet du Recteur)', 'type' => 'rectorat', 'code' => 'RECT'],
            ['nom' => 'Vice-Rectorat', 'type' => 'vice_rectorat', 'code' => 'VRECT'],
        ];
        foreach ($lieux as $l) LieuAffectation::create($l);

        // Jours fériés Sénégal 2024, 2025, 2026
        $feries = [
            // 2024
            ['nom' => "Jour de l'An", 'date' => '2024-01-01', 'annee' => 2024, 'recurrent' => true],
            ['nom' => "Magal de Touba", 'date' => '2024-01-27', 'annee' => 2024, 'recurrent' => false],
            ['nom' => "Korité (Aïd el Fitr)", 'date' => '2024-04-10', 'annee' => 2024, 'recurrent' => false],
            ['nom' => "Fête nationale", 'date' => '2024-04-04', 'annee' => 2024, 'recurrent' => true],
            ['nom' => "Fête du Travail", 'date' => '2024-05-01', 'annee' => 2024, 'recurrent' => true],
            ['nom' => "Ascension", 'date' => '2024-05-09', 'annee' => 2024, 'recurrent' => false],
            ['nom' => "Tabaski (Aïd el Adha)", 'date' => '2024-06-17', 'annee' => 2024, 'recurrent' => false],
            ['nom' => "Assomption", 'date' => '2024-08-15', 'annee' => 2024, 'recurrent' => true],
            ['nom' => "Tamkharit (Achoura)", 'date' => '2024-07-07', 'annee' => 2024, 'recurrent' => false],
            ['nom' => "Maouloud (Mawlid)", 'date' => '2024-09-16', 'annee' => 2024, 'recurrent' => false],
            ['nom' => "Toussaint", 'date' => '2024-11-01', 'annee' => 2024, 'recurrent' => true],
            ['nom' => "Noël", 'date' => '2024-12-25', 'annee' => 2024, 'recurrent' => true],
            // 2025
            ['nom' => "Jour de l'An", 'date' => '2025-01-01', 'annee' => 2025, 'recurrent' => true],
            ['nom' => "Fête nationale", 'date' => '2025-04-04', 'annee' => 2025, 'recurrent' => true],
            ['nom' => "Korité", 'date' => '2025-03-30', 'annee' => 2025, 'recurrent' => false],
            ['nom' => "Fête du Travail", 'date' => '2025-05-01', 'annee' => 2025, 'recurrent' => true],
            ['nom' => "Ascension", 'date' => '2025-05-29', 'annee' => 2025, 'recurrent' => false],
            ['nom' => "Tabaski", 'date' => '2025-06-07', 'annee' => 2025, 'recurrent' => false],
            ['nom' => "Assomption", 'date' => '2025-08-15', 'annee' => 2025, 'recurrent' => true],
            ['nom' => "Maouloud", 'date' => '2025-09-04', 'annee' => 2025, 'recurrent' => false],
            ['nom' => "Toussaint", 'date' => '2025-11-01', 'annee' => 2025, 'recurrent' => true],
            ['nom' => "Noël", 'date' => '2025-12-25', 'annee' => 2025, 'recurrent' => true],
            // 2026
            ['nom' => "Jour de l'An", 'date' => '2026-01-01', 'annee' => 2026, 'recurrent' => true],
            ['nom' => "Fête nationale", 'date' => '2026-04-04', 'annee' => 2026, 'recurrent' => true],
            ['nom' => "Korité", 'date' => '2026-03-20', 'annee' => 2026, 'recurrent' => false],
            ['nom' => "Fête du Travail", 'date' => '2026-05-01', 'annee' => 2026, 'recurrent' => true],
            ['nom' => "Tabaski", 'date' => '2026-05-27', 'annee' => 2026, 'recurrent' => false],
            ['nom' => "Assomption", 'date' => '2026-08-15', 'annee' => 2026, 'recurrent' => true],
            ['nom' => "Toussaint", 'date' => '2026-11-01', 'annee' => 2026, 'recurrent' => true],
            ['nom' => "Noël", 'date' => '2026-12-25', 'annee' => 2026, 'recurrent' => true],
        ];
        foreach ($feries as $f) JourFerie::create($f);

        // Agents de test (répartis sur les différentes structures)
        $this->call(AgentSeeder::class);
    }
}
