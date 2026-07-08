@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', 'Mon tableau de bord')

@section('content')
<style>
    .dashboard-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04) !important;
    }
    .dashboard-stat {
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.5rem;
    }
    .dashboard-stat .stat-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #64748b;
        font-weight: 700;
    }
    .dashboard-stat .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
    }
    .dashboard-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 700;
    }
    .dashboard-card table thead th {
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .dashboard-card table tbody tr td {
        vertical-align: middle;
        color: #475569;
    }
</style>

<div class="row gy-4">
    <div class="col-lg-4">
        <div class="card dashboard-card p-4">
            <h5 class="mb-2">Bonjour {{ $user->name }}</h5>
            <p class="text-muted mb-3">{{ $user->role === 'admin' ? 'Administrateur DRH' : 'Gestionnaire' }}</p>

            @if($agent)
                <p class="mb-2"><strong>Agent :</strong> {{ $agent->nom_complet }}</p>
                <p class="mb-2"><strong>Matricule :</strong> {{ $agent->matricule_solde }}</p>
                <p class="mb-3"><strong>UFR :</strong> {{ $agent->lieuAffectation?->nom ?? 'Non défini' }}</p>

                <div class="bg-light rounded-3 p-3">
                    <p class="text-uppercase small text-muted mb-2">Solde annuel</p>
                    <h4 class="mb-1">{{ $agent->conges_dus }} jours</h4>
                    <small class="text-success">{{ $agent->jours_restants }} jours restants</small>
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    Votre compte n'est pas encore associé à un agent. Contactez l'administrateur RH.
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        @if($agent)
            <div class="row gy-4">
                <div class="col-md-4">
                    <div class="card dashboard-card p-3 text-center dashboard-stat">
                        <p class="stat-title">Congés dus</p>
                        <div class="stat-value">{{ $agent->conges_dus }}</div>
                        <small class="text-muted">jours</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card p-3 text-center dashboard-stat">
                        <p class="stat-title">Congés demandés</p>
                        <div class="stat-value">{{ $agent->jours_a_prendre }}</div>
                        <small class="text-muted">jours</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card p-3 text-center dashboard-stat">
                        <p class="stat-title">Jours restants</p>
                        <div class="stat-value">{{ $agent->jours_restants }}</div>
                        <small class="text-muted">jours</small>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 my-4">
                <div>
                    <h5 class="mb-1">Actions rapides</h5>
                    <p class="text-muted small mb-0">Déposez une demande ou enregistrez une absence en quelques clics.</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('conges.create', $agent) }}" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i> Demander un congé
                    </a>
                    <a href="{{ route('absences.create', $agent) }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-times me-2"></i> Déclarer une absence
                    </a>
                </div>
            </div>

            <div class="card dashboard-card mb-4">
                <div class="card-header">Mes congés</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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
                                        <td>
                                            <span class="badge bg-{{ $conge->statut === 'approuve' ? 'success' : ($conge->statut === 'en_attente' ? 'warning text-dark' : 'secondary') }}">
                                                {{ ucfirst($conge->statut) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Aucun congé enregistré.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card dashboard-card">
                <div class="card-header">Mes absences</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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
                                        <td>{{ ucfirst($absence->type) }}</td>
                                        <td>{{ $absence->motif ?? $absence->motif_exceptionnel ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-3">Aucune absence enregistrée.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card dashboard-card p-4">
                <h5>Informations manquantes</h5>
                <p class="text-muted mb-0">Votre compte n'est pas encore lié à un profil agent. Sans cette association, le solde des congés ne peut pas être calculé et les actions ne sont pas disponibles.</p>
            </div>
        @endif
    </div>
</div>
@endsection
