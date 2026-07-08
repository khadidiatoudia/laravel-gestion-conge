<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model {
    protected $fillable = [
        'agent_id', 'date_debut', 'date_fin', 'nombre_jours',
        'type', 'motif_exceptionnel', 'motif', 'annee', 'deductible'
    ];
    protected $casts = ['date_debut' => 'date', 'date_fin' => 'date'];

    public function agent(): BelongsTo {
        return $this->belongsTo(Agent::class);
    }

    public function getMotifLibelleAttribute(): string {
        return match($this->motif_exceptionnel) {
            'mariage' => 'Mariage',
            'bapteme' => 'Baptême',
            'deces_pere' => 'Décès du père',
            'deces_mere' => 'Décès de la mère',
            'deces_epouse' => 'Décès de l\'épouse',
            'deces_enfant' => 'Décès d\'un enfant',
            default => $this->motif ?? 'Absence ordinaire',
        };
    }
}
