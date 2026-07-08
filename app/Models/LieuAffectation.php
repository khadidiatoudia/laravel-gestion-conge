<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LieuAffectation extends Model {

    // Correction de l'erreur du développeur sur le nom de la table
    protected $table = 'lieux_affectation';

    protected $fillable = ['nom', 'type', 'code'];

    public function agents(): HasMany {
        return $this->hasMany(Agent::class);
    }

    public function getTypeLibelleAttribute(): string {
        return match($this->type) {
            'direction' => 'Direction',
            'ufr' => 'UFR',
            'rectorat' => 'Rectorat',
            'vice_rectorat' => 'Vice-Rectorat',
            default => $this->type,
        };
    }
}
