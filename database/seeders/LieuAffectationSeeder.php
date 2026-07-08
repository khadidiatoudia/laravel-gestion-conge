<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LieuAffectationSeeder extends Seeder
{
    public function run(): void
    {
        $structures = [
            ['code' => 'SAEPAN', 'type' => 'ufr', 'nom' => "UFR Sciences Agronomiques, d'Élevage, de Pêche-Aquaculture et de Nutrition"],
            ['code' => 'SFI',    'type' => 'ufr', 'nom' => "UFR Sciences Fondamentales et de l'Ingénieur"],
            ['code' => 'SSE',    'type' => 'ufr', 'nom' => "UFR Sciences Sociales et Environnementles"],
            ['code' => 'SEJT',   'type' => 'ufr', 'nom' => "UFR Sciences Économiques, Juridiques et Touristiques"],
            ['code' => 'RECT',   'type' => 'rectorat', 'nom' => "Rectorat et Services Administratifs Centraux"],
        ];

        foreach ($structures as $struct) {
            // On utilise DB::table pour bypasser les Mutators/Casts du modèle qui forcent les majuscules
            DB::table('lieux_affectation')->updateOrInsert(
                ['code' => $struct['code']],
                [
                    'nom' => $struct['nom'],
                    'type' => $struct['type'], // Injecté de force en minuscules conformes à la migration
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
