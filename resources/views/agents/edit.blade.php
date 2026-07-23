@extends('layouts.app')
@section('title', 'Modifier Agent')
@section('page-title', 'Modifier un Agent')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header"><i class="fas fa-edit me-2 text-primary"></i>Modifier : {{ $agent->nom_complet }}</div>
    <div class="card-body">
    <form method="POST" action="{{ route('agents.update', $agent) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-500">Prénom *</label>
                <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $agent->prenom) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Nom *</label>
                <input type="text" name="nom" class="form-control" value="{{ old('nom', $agent->nom) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Matricule de Solde *</label>
                <input type="text" name="matricule_solde" class="form-control" value="{{ old('matricule_solde', $agent->matricule_solde) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Lieu d'Affectation *</label>
                <select name="lieu_affectation_id" class="form-select" required>
                    @foreach($structures as $l)
                    <option value="{{ $l->id }}" {{ old('lieu_affectation_id', $agent->lieu_affectation_id) == $l->id ? 'selected' : '' }}>{{ $l->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Corps du personnel *</label>
                <select name="type_personnel" class="form-select @error('type_personnel') is-invalid @enderror" required>
                    <option value="PER" {{ old('type_personnel', $agent->type_personnel) == 'PER' ? 'selected' : '' }}>PER (Enseignant / Recherche)</option>
                    <option value="PATS" {{ old('type_personnel', $agent->type_personnel) == 'PATS' ? 'selected' : '' }}>PATS (Admin / Technique / Service)</option>
                </select>
                @error('type_personnel') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500">Date de Prise de Service *</label>
                <input type="date" name="date_prise_service" class="form-control" value="{{ old('date_prise_service', $agent->date_prise_service->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-500">Sexe</label>
                <select name="sexe" class="form-select" id="sexeSelect">
                    <option value="M" {{ old('sexe', $agent->sexe) === 'M' ? 'selected' : '' }}>Masculin</option>
                    <option value="F" {{ old('sexe', $agent->sexe) === 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
            </div>
            <div class="col-md-3" id="enfantsDiv" style="{{ old('sexe', $agent->sexe) === 'F' ? '' : 'display:none' }}">
                <label class="form-label fw-500">Nombre d'enfants</label>
                <input type="number" name="nombre_enfants" class="form-control" value="{{ old('nombre_enfants', $agent->nombre_enfants) }}" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-500">Jours reportés (N-1)</label>
                <input type="number" name="conges_reportes" class="form-control" value="{{ old('conges_reportes', $agent->conges_reportes) }}" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-500">Congés exceptionnels (Recteur)</label>
                <input type="number" name="conges_exceptionnels" class="form-control" value="{{ old('conges_exceptionnels', $agent->conges_exceptionnels) }}" min="0">
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Mettre à jour</button>
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
document.getElementById('sexeSelect').addEventListener('change', function() {
    document.getElementById('enfantsDiv').style.display = this.value === 'F' ? '' : 'none';
});
</script>
@endsection
