@extends('layouts.app')
@section('title', 'Ajouter un agent')
@section('page-title', 'Nouveau Profil Personnel')

@section('content')
<style>
    .custom-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
    }
    .input-custom, .select-custom {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        height: 44px;
        font-size: 0.9rem;
        color: #334155;
    }
    .input-custom:focus, .select-custom:focus {
        border-color: #15803d !important;
        box-shadow: 0 0 0 3px rgba(21, 163, 74, 0.1) !important;
    }
    .btn-green-primary {
        background-color: #15803d !important;
        border-color: #15803d !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        font-weight: 600;
        height: 44px;
        transition: all 0.2s ease;
    }
    .btn-green-primary:hover {
        background-color: #166534 !important;
        transform: translateY(-1px);
    }
    .btn-outline-custom {
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        color: #475569 !important;
        border-radius: 8px !important;
        font-weight: 500;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-outline-custom:hover {
        background-color: #f8fafc !important;
        color: #1e293b !important;
    }
    .form-label-custom {
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }
</style>

<div class="mb-4">
    <a href="{{ route('agents.index') }}" class="text-success text-decoration-none small fw-600">
        <i class="fas fa-arrow-left me-1"></i> Retour à la liste du personnel
    </a>
    <h4 class="fw-700 text-dark mb-0 mt-2">Enregistrer un nouvel agent</h4>
    <p class="text-muted mb-0 small">Renseignez les informations d'affectation institutionnelle de l'agent.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card custom-card">
            <div class="card-body p-4">
                <form action="{{ route('agents.store') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-3">
                        <!-- Prénom -->
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" class="form-control input-custom @error('prenom') is-invalid @enderror" value="{{ old('prenom') }}" placeholder="Ex: El Hadji" required>
                            @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Nom -->
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control input-custom @error('nom') is-invalid @enderror" value="{{ old('nom') }}" placeholder="Ex: Niass" required>
                            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Matricule -->
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">N° Matricule <span class="text-danger">*</span></label>
                            <input type="text" name="matricule_solde" class="form-control input-custom @error('matricule_solde') is-invalid @enderror" value="{{ old('matricule_solde') }}" placeholder="Ex: USS-2026-X" required>
                            @error('matricule_solde') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Corps de métier universitaire -->
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Corps de métier (Type) <span class="text-danger">*</span></label>
                            <select name="type_personnel" class="form-select select-custom @error('type_personnel') is-invalid @enderror" required>
                                <option value="" disabled selected>Sélectionner le corps...</option>
                                <option value="PER" {{ old('type_personnel') == 'PER' ? 'selected' : '' }}>PER (Enseignant / Recherche)</option>
                                <option value="PATS" {{ old('type_personnel') == 'PATS' ? 'selected' : '' }}>PATS (Admin / Technique / Service)</option>
                            </select>
                            @error('type_personnel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Structure / UFR -->
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">UFR / Structure d'affectation <span class="text-danger">*</span></label>
                            <select name="lieu_affectation_id" class="form-select select-custom @error('lieu_affectation_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Choisir une composante...</option>

                                @if(isset($structures) && $structures->count() > 0)
                                    {{-- Affichage dynamique si les données existent en base --}}
                                    @foreach($structures as $struct)
                                        <option value="{{ $struct->id }}" {{ old('lieu_affectation_id') == $struct->id ? 'selected' : '' }}>
                                            {{ $struct->code }} - {{ $struct->nom }}
                                        </option>
                                    @endforeach
                                @else
                                    {{-- Sécurité : Données statiques de secours de l'USSEIN si la table est vide --}}
                                    <option value="1" {{ old('lieu_affectation_id') == '1' ? 'selected' : '' }}>SAEPAN - UFR Sciences Agronomiques, d'Élevage, de Pêche-Aquaculture et de Nutrition</option>
                                    <option value="2" {{ old('lieu_affectation_id') == '2' ? 'selected' : '' }}>SFI - UFR Sciences Fondamentales et de l'Ingénieur</option>
                                    <option value="3" {{ old('lieu_affectation_id') == '3' ? 'selected' : '' }}>SSE - UFR Sciences Sociales et Environnementles</option>
                                    <option value="4" {{ old('lieu_affectation_id') == '4' ? 'selected' : '' }}>SEJT - UFR Sciences Économiques, Juridiques et Touristiques</option>
                                    <option value="5" {{ old('lieu_affectation_id') == '5' ? 'selected' : '' }}>RECT - Rectorat et Services Administratifs Centraux</option>
                                @endif
                            </select>
                            @error('lieu_affectation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Date de prise de service <span class="text-danger">*</span></label>
                            <input type="date" name="date_prise_service" class="form-control input-custom @error('date_prise_service') is-invalid @enderror" value="{{ old('date_prise_service') }}" required>
                            @error('date_prise_service') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Sexe <span class="text-danger">*</span></label>
                            <select name="sexe" class="form-select select-custom @error('sexe') is-invalid @enderror" required>
                                <option value="" disabled selected>Choisir le sexe...</option>
                                <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Nombre d'enfants</label>
                            <input type="number" name="nombre_enfants" class="form-control input-custom @error('nombre_enfants') is-invalid @enderror" value="{{ old('nombre_enfants', 0) }}" min="0">
                            @error('nombre_enfants') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Congés reportés (N-1)</label>
                            <input type="number" name="conges_reportes" class="form-control input-custom @error('conges_reportes') is-invalid @enderror" value="{{ old('conges_reportes', 0) }}" min="0">
                            @error('conges_reportes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Congés exceptionnels</label>
                            <input type="number" name="conges_exceptionnels" class="form-control input-custom @error('conges_exceptionnels') is-invalid @enderror" value="{{ old('conges_exceptionnels', 0) }}" min="0">
                            @error('conges_exceptionnels') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="text-muted my-4 opacity-25">

                    <!-- Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('agents.index') }}" class="btn btn-outline-custom px-4">Annuler</a>
                        <button type="submit" class="btn btn-green-primary px-4">
                            <i class="fas fa-save me-2"></i> Enregistrer l'agent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
