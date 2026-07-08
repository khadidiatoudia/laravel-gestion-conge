<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conge extends Model {
    protected $fillable = [
        'agent_id', 'jours_a_prendre', 'date_cessation_service',
        'date_reprise_service', 'annee', 'statut', 'observations', 'deductible'
    ];
    protected $casts = [
        'date_cessation_service' => 'date',
        'date_reprise_service' => 'date',
    ];

    public function agent(): BelongsTo {
        return $this->belongsTo(Agent::class);
    }

    public function getStatutBadgeAttribute(): string {
        return match($this->statut) {
            'en_attente' => '<span class="badge bg-warning">En attente</span>',
            'approuve' => '<span class="badge bg-success">Approuvé</span>',
            'termine' => '<span class="badge bg-secondary">Terminé</span>',
            'annule' => '<span class="badge bg-danger">Annulé</span>',
            default => '<span class="badge bg-light">-</span>',
        };
    }
}
