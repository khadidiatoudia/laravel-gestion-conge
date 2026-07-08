@extends('layouts.app')
@section('title', 'Mon compte')
@section('page-title', 'Mon espace personnel')
@section('content')
<div class="row gy-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h5>Bienvenue</h5>
            <p class="text-muted">{{ $user->name }}</p>
            <p><strong>Role :</strong> {{ $user->role === 'admin' ? 'Administrateur' : 'Utilisateur' }}</p>
            @if($user->agent)
                <p><strong>Agent :</strong> {{ $user->agent->nom_complet }}</p>
                <p><strong>Matricule :</strong> {{ $user->agent->matricule_solde }}</p>
                <p><strong>UFR :</strong> {{ $user->agent->lieuAffectation->nom }}</p>
            @else
                <div class="alert alert-warning">Aucun agent associé. Demandez au service RH d'associer votre compte à votre profil agent.</div>
            @endif
        </div>
    </div>
    <div class="col-lg-8">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <p class="text-muted small mb-1">Congés dus</p>
                    <h3>{{ $user->agent?->conges_dus ?? 0 }}</h3>
                    <small class="text-muted">jours</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <p class="text-muted small mb-1">Jours pris</p>
                    <h3>{{ $user->agent?->jours_a_prendre ?? 0 }}</h3>
                    <small class="text-muted">jours</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 text-center">
                    <p class="text-muted small mb-1">Jours restants</p>
                    <h3>{{ $user->agent?->jours_restants ?? 0 }}</h3>
                    <small class="text-muted">jours</small>
                </div>
            </div>
        </div>

        @if($user->agent)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-0">Actions rapides</h5>
                    <p class="text-muted small mb-0">Demandez un congé ou consultez votre historique.</p>
                </div>
                @if(!$user->isAdmin())
                    <a href="{{ route('conges.create', $agent) }}" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i> Demander un congé
                    </a>
                @endif
            </div>

            <div class="card mb-4">
                <div class="card-header"><strong>Mes congés</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Date début</th>
                                    <th>Date reprise</th>
                                    <th>Jours</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agent->conges as $conge)
                                    <tr>
                                        <td>{{ $conge->date_cessation_service->format('d/m/Y') }}</td>
                                        <td>{{ $conge->date_reprise_service?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $conge->jours_a_prendre }}</td>
                                        <td>{{ $conge->statut }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Aucun congé</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header"><strong>Mes absences</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Période</th>
                                    <th>Jours</th>
                                    <th>Type</th>
                                    <th>Motif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agent->absences as $absence)
                                    <tr>
                                        <td>{{ $absence->date_debut->format('d/m/Y') }} - {{ $absence->date_fin->format('d/m/Y') }}</td>
                                        <td>{{ $absence->nombre_jours }}</td>
                                        <td>{{ $absence->type }}</td>
                                        <td>{{ $absence->motif ?? $absence->motif_exceptionnel ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Aucune absence</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
