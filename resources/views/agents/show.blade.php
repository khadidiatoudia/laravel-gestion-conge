@extends('layouts.app')
@section('title', $agent->nom_complet)
@section('page-title', 'Fiche Agent')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width:56px;height:56px;border-radius:14px;background:#1a3a5c;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.2rem">
            {{ strtoupper(substr($agent->prenom,0,1).substr($agent->nom,0,1)) }}
        </div>
        <div>
            <h4 class="fw-bold mb-0">{{ $agent->nom_complet }}</h4>
            <small class="text-muted"><code>{{ $agent->matricule_solde }}</code> &middot; {{ $agent->lieuAffectation->nom }}</small>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('absences.create', $agent) }}" class="btn btn-warning text-dark"><i class="fas fa-calendar-times me-1"></i>Saisir absence</a>
        <a href="{{ route('conges.create', $agent) }}" class="btn btn-success"><i class="fas fa-umbrella-beach me-1"></i>Saisir congé</a>
        <a href="{{ route('agents.edit', $agent) }}" class="btn btn-outline-secondary"><i class="fas fa-edit me-1"></i>Modifier</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card p-3">
            <div class="card-header bg-white border-bottom-0">
                <strong>Compte utilisateur lié</strong>
            </div>
            <div class="card-body">
                @if($agent->user)
                    <p class="mb-2"><strong>Nom :</strong> {{ $agent->user->name }}</p>
                    <p class="mb-2"><strong>Email :</strong> {{ $agent->user->email }}</p>
                    <form action="{{ route('agents.unlinkUser', $agent) }}" method="POST" onsubmit="return confirm('Dissocier ce compte utilisateur de l\'agent ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Dissocier le compte</button>
                    </form>
                @else
                    <p class="text-muted small mb-3">Aucun compte utilisateur n'est encore associé à cet agent.</p>
                    <form action="{{ route('agents.linkUser', $agent) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email utilisateur</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Associer un compte</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center p-3" style="border-left:4px solid #1a3a5c">
                    <p class="text-muted small mb-1">Congés Dus</p>
                    <h2 class="fw-bold mb-0 text-primary">{{ $agent->conges_dus }}</h2>
                    <small class="text-muted">jours</small>
                    @if($agent->conges_dus >= 60)<div class="mt-1"><span class="badge bg-danger" style="font-size:.7rem">Solde élevé</span></div>@endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3" style="border-left:4px solid #10b981">
                    <p class="text-muted small mb-1">Jours à Prendre</p>
                    <h2 class="fw-bold mb-0 text-success">{{ $agent->jours_a_prendre }}</h2>
                    <small class="text-muted">jours</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3" style="border-left:4px solid #f59e0b">
                    <p class="text-muted small mb-1">Absences Déduites</p>
                    <h2 class="fw-bold mb-0 text-warning">{{ $agent->absences_deductibles_annee }}</h2>
                    <small class="text-muted">jours {{ now()->year }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3" style="border-left:4px solid #8b5cf6">
            <p class="text-muted small mb-1">Jours Restants</p>
            <h2 class="fw-bold mb-0" style="color:#7c3aed">{{ $agent->jours_restants }}</h2>
            <small class="text-muted">jours</small>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-info-circle me-2 text-primary"></i>Informations</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted small">Date prise service</td><td class="fw-500">{{ $agent->date_prise_service->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted small">Ancienneté</td><td class="fw-500">{{ $agent->date_prise_service->diffForHumans(null, true) }}</td></tr>
                    <tr><td class="text-muted small">Sexe</td><td class="fw-500">{{ $agent->sexe === 'M' ? 'Masculin' : 'Féminin' }}</td></tr>
                    @if($agent->sexe === 'F')
                    <tr><td class="text-muted small">Nombre enfants</td><td class="fw-500">{{ $agent->nombre_enfants }} (+{{ $agent->bonus_enfants }}j bonus)</td></tr>
                    @endif
                    <tr><td class="text-muted small">Congés annuels</td><td class="fw-500">{{ $agent->conges_annuels }} jours</td></tr>
                    <tr><td class="text-muted small">Report N-1</td><td class="fw-500">{{ $agent->conges_reportes }} jours</td></tr>
                    <tr><td class="text-muted small">Congés exceptionnels</td><td class="fw-500">{{ $agent->conges_exceptionnels }} jours</td></tr>
                    @if($agent->date_cessation)
                    <tr><td class="text-muted small">Dernière cessation</td><td class="fw-500">{{ \Carbon\Carbon::parse($agent->date_cessation)->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted small">Dernière reprise</td><td class="fw-500">{{ \Carbon\Carbon::parse($agent->date_reprise)->format('d/m/Y') }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-umbrella-beach me-2 text-success"></i>Historique Congés</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Cessation</th><th>Reprise</th><th>Jours</th><th>Statut</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($agent->conges as $c)
                        <tr>
                            <td>{{ $c->date_cessation_service->format('d/m/Y') }}</td>
                            <td>{{ $c->date_reprise_service?->format('d/m/Y') ?? '-' }}</td>
                            <td><strong>{{ $c->jours_a_prendre }}</strong>j @if(!$c->deductible)<span class="badge bg-info text-dark ms-1" style="font-size:.65rem">Non déduit</span>@endif</td>
                            <td>{!! $c->statut_badge !!}</td>
                            <td class="d-flex gap-2">
                                @if($c->statut === 'en_attente')
                                    <form method="POST" action="{{ route('conges.statut', $c) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="statut" value="approuve">
                                        <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                                    </form>
                                    <form method="POST" action="{{ route('conges.statut', $c) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="statut" value="annule">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Refuser</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('conges.destroy', $c) }}" onsubmit="return confirm('Supprimer ce congé ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Aucun congé enregistré</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-calendar-times me-2 text-warning"></i>Historique Absences</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Période</th><th>Jours</th><th>Type</th><th>Motif</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($agent->absences as $a)
                        <tr>
                            <td>{{ $a->date_debut->format('d/m/Y') }} &rarr; {{ $a->date_fin->format('d/m/Y') }}</td>
                            <td><strong>{{ $a->nombre_jours }}</strong>j</td>
                            <td>@if($a->deductible)<span class="badge bg-warning text-dark">Déduite</span>@else<span class="badge bg-info text-dark">Exceptionnelle</span>@endif</td>
                            <td><small>{{ $a->motif_libelle }}</small></td>
                            <td>
                                <form method="POST" action="{{ route('absences.destroy', $a) }}" onsubmit="return confirm('Supprimer cette absence ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Aucune absence enregistrée</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Retour à la liste</a>
</div>
@endsection
