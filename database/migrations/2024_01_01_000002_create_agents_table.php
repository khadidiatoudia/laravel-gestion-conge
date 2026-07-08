<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule_solde')->unique();
            $table->foreignId('lieu_affectation_id')->constrained('lieux_affectation');
            $table->date('date_prise_service');
            $table->enum('sexe', ['M', 'F'])->default('M');
            $table->integer('nombre_enfants')->default(0);
            $table->integer('conges_reportes')->default(0); // jours N-1 reportés
            $table->integer('conges_exceptionnels')->default(0); // accordés par Recteur
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('agents'); }
};
