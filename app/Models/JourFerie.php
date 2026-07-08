<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JourFerie extends Model {

    // On force Laravel à pointer sur la table au pluriel créée par la migration
    protected $table = 'jours_feries';

    protected $fillable = ['nom', 'date', 'annee', 'recurrent'];
    protected $casts = ['date' => 'date'];
}
