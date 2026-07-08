@extends('layouts.app')
@section('title', 'Saisir une Absence')
@section('page-title', 'Saisir une Absence')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="fas fa-calendar-times me-2 text-warning"></i>Absence pour : <strong>{{ $agent->nom_complet }}</strong></div>
    <div class="card-body">
        <div class="alert alert-info d-flex gap-2 mb-4">
            <i class="fas fa-info-circle mt-1"></i>
            <div>
                <strong>Solde actuel :</strong> {{ $agent->conges_dus }} jours de congé dus &middot; {{ $agent->absences_deductibles_annee }} jours d'absences déjà déduites en {{ now()->year }}
            </div>
        </div>
        <form method="POST" action="{{ route('absences.store', $agent) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-500">Date de début *</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Nombre de jours *</label>
                    <input type="number" name="nombre_jours" class="form-control" value="{{ old('nombre_jours', 1) }}" min="1" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-500">Type d'absence *</label>
                    <select name="type" class="form-select" id="typeSelect">
                        <option value="ordinaire" {{ old('type') !== 'exceptionnel' ? 'selected' : '' }}>Ordinaire (déduite du congé)</option>
                        <option value="exceptionnel" {{ old('type') === 'exceptionnel' ? 'selected' : '' }}>Exceptionnelle (non déduite)</option>
                    </select>
                </div>
                <div class="col-12" id="motifExDiv" style="{{ old('type') === 'exceptionnel' ? '' : 'display:none' }}">
                    <label class="form-label fw-500">Motif exceptionnel *</label>
                    <select name="motif_exceptionnel" class="form-select">
                        <option value="">-- Sélectionner --</option>
                        <option value="mariage" {{ old('motif_exceptionnel') === 'mariage' ? 'selected' : '' }}>Mariage</option>
                        <option value="bapteme" {{ old('motif_exceptionnel') === 'bapteme' ? 'selected' : '' }}>Baptême</option>
                        <option value="deces_pere" {{ old('motif_exceptionnel') === 'deces_pere' ? 'selected' : '' }}>Décès du père</option>
                        <option value="deces_mere" {{ old('motif_exceptionnel') === 'deces_mere' ? 'selected' : '' }}>Décès de la mère</option>
                        <option value="deces_epouse" {{ old('motif_exceptionnel') === 'deces_epouse' ? 'selected' : '' }}>Décès de l'épouse</option>
                        <option value="deces_enfant" {{ old('motif_exceptionnel') === 'deces_enfant' ? 'selected' : '' }}>Décès d'un enfant</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-500">Observations</label>
                    <textarea name="motif" class="form-control" rows="2" placeholder="Notes complémentaires...">{{ old('motif') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning text-dark"><i class="fas fa-save me-2"></i>Enregistrer l'absence</button>
                <a href="{{ route('agents.show', $agent) }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>
document.getElementById('typeSelect').addEventListener('change', function() {
    document.getElementById('motifExDiv').style.display = this.value === 'exceptionnel' ? '' : 'none';
});
</script>
@endsection
