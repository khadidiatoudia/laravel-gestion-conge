<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lieux_affectation', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('type')->default('ufr'); // direction, ufr, rectorat, vice_rectorat
            $table->string('code')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lieux_affectation');
    }
};
