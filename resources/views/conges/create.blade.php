@extends('layouts.app')
@section('title', 'Saisir un Congé')
@section('page-title', 'Saisir un Congé')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="fas fa-umbrella-beach me-2 text-success"></i>Congé pour : <strong>{{ $agent->nom_complet }}</strong></div>
    <div class="card-body">
        <div class="alert alert-success d-flex gap-2 mb-4">
            <i class="fas fa-info-circle mt-1"></i>
            <div>
                <strong>Congés disponibles :</strong> {{ $agent->conges_dus }} jours &middot; <strong>Jours restants :</strong> {{ $agent->jours_restants }} jours
                <br><small>Les dates de retour sont calculées automatiquement (lundi-samedi, hors fériés)</small>
            </div>
        </div>
        <form method="POST" action="{{ route('conges.store', $agent) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-500">Nombre de jours à prendre *</label>
                    <input type="number" name="jours_a_prendre" class="form-control @error('jours_a_prendre') is-invalid @enderror" value="{{ old('jours_a_prendre') }}" min="1" max="{{ $agent->conges_dus }}" required>
                    <small class="text-muted">Maximum {{ $agent->conges_dus }} jours</small>
                    @error('jours_a_prendre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-500">Date de Cessation de Service *</label>
                    <input type="date" name="date_cessation_service" class="form-control" value="{{ old('date_cessation_service') }}" required>
                    <small class="text-muted">Jour du départ. Si vendredi, le comptage commence lundi</small>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="deductible" id="deductible" value="1" {{ old('deductible', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="deductible">
                            Déduire des congés disponibles
                            <small class="text-muted">(décocher pour congé exceptionnel accordé par le Recteur)</small>
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-500">Observations</label>
                    <textarea name="observations" class="form-control" rows="2">{{ old('observations') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-success"><i class="fas fa-calculator me-2"></i>Calculer et Enregistrer</button>
                <a href="{{ route('agents.show', $agent) }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
