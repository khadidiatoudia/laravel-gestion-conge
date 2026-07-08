<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->integer('jours_a_prendre');
            $table->date('date_cessation_service');
            $table->date('date_reprise_service')->nullable(); // calculée auto
            $table->integer('annee');
            $table->enum('statut', ['en_attente', 'approuve', 'termine', 'annule'])->default('en_attente');
            $table->text('observations')->nullable();
            $table->boolean('deductible')->default(true); // exceptionnel Recteur
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('conges'); }
};
