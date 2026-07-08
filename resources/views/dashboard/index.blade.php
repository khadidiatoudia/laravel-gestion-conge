@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', 'Vue d\'ensemble analytique USSEIN')

@section('content')
<style>
    .dashboard-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
    }
    .card-indicator {
        width: 5px;
        position: absolute;
        left: 0; top: 0; bottom: 0;
    }
    .icon-box {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px;
    }
    .text-muted-dark {
        color: #64748b !important;
        font-weight: 700;
        font-size: 0.72rem;
        letter-spacing: 0.8px;
    }
    .stat-number {
        color: #0f172a;
        font-weight: 700;
        font-size: 2rem;
        line-height: 1;
    }
    .card-title-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }
</style>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card dashboard-card position-relative overflow-hidden">
            <div class="card-indicator" style="background-color: #15803d;"></div>
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-2 text-muted-dark text-uppercase">Effectif Global</p>
                    <h3 class="stat-number mb-0">{{ $totalAgents }}</h3>
                </div>
                <div class="icon-box" style="background-color: #f0fdf4;">
                    <i class="fas fa-users" style="color: #15803d;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card position-relative overflow-hidden">
            <div class="card-indicator" style="background-color: #16a34a;"></div>
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-2 text-muted-dark text-uppercase">En Congé Actuel</p>
                    <h3 class="stat-number mb-0">{{ $congesEnCours }}</h3>
                </div>
                <div class="icon-box" style="background-color: #f0fdf4;">
                    <i class="fas fa-umbrella-beach" style="color: #16a34a;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card position-relative overflow-hidden">
            <div class="card-indicator" style="background-color: #0284c7;"></div>
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-2 text-muted-dark text-uppercase">Enseignants (PER)</p>
                    <h3 class="stat-number mb-0">{{ $totalPER ?? 0 }}</h3>
                </div>
                <div class="icon-box" style="background-color: #f0f9ff;">
                    <i class="fas fa-graduation-cap" style="color: #0284c7;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card dashboard-card position-relative overflow-hidden">
            <div class="card-indicator" style="background-color: #b45309;"></div>
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-2 text-muted-dark text-uppercase">Admin &amp; Tech (PATS)</p>
                    <h3 class="stat-number mb-0">{{ $totalPATS ?? 0 }}</h3>
                </div>
                <div class="icon-box" style="background-color: #fffbeb;">
                    <i class="fas fa-user-cog" style="color: #b45309;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <span class="card-title-text"><i class="fas fa-leaf text-success me-2"></i>Ressources Humaines par UFR</span>
                <span class="badge bg-light text-success border border-success-subtle fw-normal">Pôles USSEIN</span>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 260px;">
                    <canvas id="structuresChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <span class="card-title-text"><i class="fas fa-walking text-success me-2"></i>Départs en Congé en cours</span>
            </div>
            <div class="card-body p-0">
                @if($agentsEnConge->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 ps-3">Agent</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">UFR / Structure</th>
                                <th class="border-0 text-end pe-3">Reprise</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agentsEnConge as $c)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-600 text-dark d-block">{{ $c->agent->nom_complet }}</span>
                                    <small class="text-muted text-uppercase" style="font-size: 0.75rem;">{{ $c->agent->matricule_solde }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $c->agent->type_personnel === 'PER' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }} fw-600">
                                        {{ $c->agent->type_personnel }}
                                    </span>
                                </td>
                                <td><span class="badge bg-light text-dark fw-normal border">{{ $c->agent->lieuAffectation->code ?? 'N/A' }}</span></td>
                                <td class="text-end pe-3"><span class="badge bg-success-subtle text-success">{{ $c->date_reprise_service?->format('d/m/Y') }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-5 text-center text-muted">
                    <p class="mb-0 small"><i class="fas fa-seedling text-success me-2"></i> Aucun mouvement de congé à signaler aujourd'hui.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <span class="card-title-text"><i class="fas fa-hourglass-half text-warning me-2"></i>Demandes de congés en attente</span>
                <span class="badge bg-warning-subtle text-warning">{{ $pendingCount ?? 0 }} en attente</span>
            </div>
            <div class="card-body p-0">
                @if($pendingConges->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 ps-3">Agent</th>
                                <th class="border-0">Début</th>
                                <th class="border-0">Jours</th>
                                <th class="border-0">UFR</th>
                                <th class="border-0 text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingConges as $c)
                            <tr>
                                <td class="ps-3">
                                    <span class="fw-600 text-dark d-block">{{ $c->agent->nom_complet }}</span>
                                    <small class="text-muted text-uppercase" style="font-size: 0.75rem;">{{ $c->agent->matricule_solde }}</small>
                                </td>
                                <td>{{ $c->date_cessation_service->format('d/m/Y') }}</td>
                                <td>{{ $c->jours_a_prendre }}j</td>
                                <td><span class="badge bg-light text-dark fw-normal border">{{ $c->agent->lieuAffectation->code ?? 'N/A' }}</span></td>
                                <td class="text-end pe-3 d-flex justify-content-end gap-2">
                                    <form method="POST" action="{{ route('conges.statut', $c) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="statut" value="approuve">
                                        <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                                    </form>
                                    <form method="POST" action="{{ route('conges.statut', $c) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="statut" value="annule">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Refuser</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-5 text-center text-muted">
                    <p class="mb-0 small"><i class="fas fa-check-circle text-success me-2"></i> Aucune demande de congé en attente.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <span class="card-title-text"><i class="fas fa-chart-pie text-success me-2"></i>Répartition par Sexe</span>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center" style="height: 292px;">
                <div style="position: relative; height: 210px; width: 100%;">
                    <canvas id="corpsPersonnelChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3">
                <span class="card-title-text" style="color: #dc2626;"><i class="fas fa-exclamation-circle me-2"></i>Soldes de Sécurité (&ge; 48j)</span>
            </div>
            <div class="card-body p-0">
                @if($agentsCritiques->count())
                <div class="table-responsive" style="max-height: 245px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <tbody>
                            @foreach($agentsCritiques as $a)
                            <tr>
                                <td class="ps-3 py-2">
                                    <span class="fw-500 text-dark">{{ $a->nom_complet }}</span>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $a->type_personnel }} &bull; UFR {{ $a->lieuAffectation->code ?? '' }}</small>
                                </td>
                                <td class="text-end pe-3 py-2">
                                    <span class="badge" style="{{ $a->conges_dus >= 60 ? 'background-color: #fef2f2; color: #dc2626;' : 'background-color: #fffbeb; color: #d97706;' }}">
                                        {{ $a->conges_dus }} j
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted">
                    <p class="mb-0 small" style="color: #64748b;"><i class="fas fa-shield-alt text-success me-1"></i> Équilibres des temps de repos maîtrisés.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // --- GRAPH 1 : HISTOGRAMME DES UFR USSEIN ---
    const ctxStructures = document.getElementById('structuresChart');
    if (ctxStructures) {
        new Chart(ctxStructures.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($ufrLabels),
                datasets: [{
                    data: @json($ufrValues),
                    backgroundColor: '#15803d',
                    borderRadius: 6,
                    barThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const fullNames = {
                                    "SSE": "Sciences Sociales et Environnementles",
                                    "SFI": "Sciences Fondamentales et de l'Ingénieur",
                                    "SAEPAN": "Sciences Agronomiques, Élevage, Pêche-Aquaculture et Nutrition",
                                    "SEJT": "Sciences Économiques, Juridiques et Touristiques",
                                    "RECT / Admin": "Services Administratifs et Rectorat"
                                };
                                return fullNames[context[0].label] || context[0].label;
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 11, weight: '600' } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#64748b', precision: 0, font: { size: 10 } }, min: 0 }
                }
            }
        });
    }

    // --- GRAPH 2 : ANNEAU DE RÉPARTITION PAR SEXE ---
    const ctxPie = document.getElementById('corpsPersonnelChart');
    if (ctxPie) {
        new Chart(ctxPie.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hommes', 'Femmes'],
                datasets: [{
                    data: [{{ $totalHommes }}, {{ $totalFemmes }}],
                    backgroundColor: ['#0284c7', '#db2777'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 15, font: { size: 11, weight: '600' }, color: '#475569' }
                    }
                },
                cutout: '75%'
            }
        });
    }
});
</script>
@endsection
