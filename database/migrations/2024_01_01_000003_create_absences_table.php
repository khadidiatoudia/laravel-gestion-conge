<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nombre_jours');
            $table->enum('type', ['ordinaire', 'exceptionnel'])->default('ordinaire');
            $table->enum('motif_exceptionnel', ['mariage', 'bapteme', 'deces_pere', 'deces_mere', 'deces_epouse', 'deces_enfant'])->nullable();
            $table->text('motif')->nullable();
            $table->integer('annee');
            $table->boolean('deductible')->default(true); // false for exceptional
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('absences'); }
};
