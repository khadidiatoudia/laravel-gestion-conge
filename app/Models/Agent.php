<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Agent extends Model {
    protected $fillable = [
        'nom', 'prenom', 'matricule_solde', 'lieu_affectation_id',
        'date_prise_service', 'sexe', 'type_personnel', 'nombre_enfants',
        'conges_reportes', 'conges_exceptionnels', 'actif'
    ];
    protected $casts = ['date_prise_service' => 'date'];

    public function lieuAffectation(): BelongsTo {
        return $this->belongsTo(LieuAffectation::class);
    }
    public function absences(): HasMany {
        return $this->hasMany(Absence::class);
    }
    public function conges(): HasMany {
        return $this->hasMany(Conge::class);
    }
    public function user(): HasOne {
        return $this->hasOne(User::class);
    }

    public function getNomCompletAttribute(): string {
        return $this->prenom . ' ' . strtoupper($this->nom);
    }

    public function getCongesAnnuelsAttribute(): int {
        $moisService = $this->date_prise_service->diffInMonths(Carbon::now());
        if ($moisService < 12) return (int) floor($moisService * 2);
        return 24;
    }

    public function getBonusEnfantsAttribute(): int {
        if ($this->sexe === 'F') return $this->nombre_enfants;
        return 0;
    }

    public function getAbsencesDeductiblesAnneeAttribute(): int {
        return $this->absences()
            ->where('annee', Carbon::now()->year)
            ->where('deductible', true)
            ->sum('nombre_jours');
    }

    public function getCongesDusAttribute(): int {
        $total = $this->conges_reportes + $this->conges_annuels + $this->bonus_enfants + $this->conges_exceptionnels - $this->absences_deductibles_annee;
        return min(max(0, $total), 72);
    }

    public function getJoursAPrendreAttribute(): int {
        return $this->conges()
            ->where('annee', Carbon::now()->year)
            ->where('deductible', true)
            ->where('statut', '!=', 'annule')
            ->sum('jours_a_prendre');
    }

    public function getJoursRestantsAttribute(): int {
        return max(0, $this->conges_dus - ($this->jours_a_prendre + $this->absences_deductibles_annee));
    }

    public function getDateCessationAttribute() {
        return $this->conges()->where('annee', Carbon::now()->year)->latest()->value('date_cessation_service');
    }

    public function getDateRepriseAttribute() {
        return $this->conges()->where('annee', Carbon::now()->year)->latest()->value('date_reprise_service');
    }
}
