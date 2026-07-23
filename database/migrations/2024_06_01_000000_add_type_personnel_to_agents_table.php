<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ajoute la colonne type_personnel, utilisée par le formulaire de
     * création/édition d'agent mais absente de la migration d'origine.
     * Valeur par défaut 'PATS' pour ne pas casser les agents déjà en base.
     */
    public function up(): void {
        Schema::table('agents', function (Blueprint $table) {
            $table->enum('type_personnel', ['PER', 'PATS'])->default('PATS')->after('sexe');
        });
    }

    public function down(): void {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('type_personnel');
        });
    }
};
