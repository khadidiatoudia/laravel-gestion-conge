@extends('layouts.app')
@section('title', 'Gestion du Personnel')
@section('page-title', 'Gestion des Agents')

@section('content')
<style>
    .custom-card { background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 12px !important; box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important; }
    .search-box { border: 1px solid #cbd5e1 !important; border-radius: 8px !important; padding-left: 40px !important; height: 42px; font-size: 0.9rem; }
    .search-box:focus { border-color: #16a34a !important; box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1) !important; }
    .search-icon { position: absolute; left: 15px; top: 13px; color: #94a3b8; }
    .select-custom { border: 1px solid #cbd5e1 !important; border-radius: 8px !important; height: 42px; font-size: 0.9rem; color: #334155; }
    .btn-green-primary { background-color: #15803d !important; border-color: #15803d !important; color: #ffffff !important; border-radius: 8px !important; font-weight: 600; height: 42px; padding: 0 20px; transition: all 0.2s ease; }
    .btn-green-primary:hover { background-color: #166534 !important; transform: translateY(-1px); }
    .btn-outline-custom { border: 1px solid #cbd5e1 !important; background-color: #ffffff !important; color: #475569 !important; border-radius: 8px !important; font-weight: 500; height: 42px; }
    .table-custom thead { background-color: #f8fafc !important; }
    .table-custom th { color: #64748b !important; font-weight: 700 !important; font-size: 0.75rem !important; text-uppercase: true; letter-spacing: 0.5px; padding: 14px 16px !important; }
    .table-custom td { padding: 14px 16px !important; color: #334155; font-size: 0.9rem; }
    .agent-avatar-text { width: 36px; height: 36px; background-color: #f0fdf4; color: #16a34a; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
    .badge-structure { background-color: #f1f5f9; color: #475569; font-weight: 600; padding: 5px 10px; border-radius: 6px; border: 1px solid #e2e8f0; }
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-700 text-dark mb-1">Gestion des Agents</h4>
        <p class="text-muted mb-0 small"><i class="fas fa-users text-success me-1"></i> Système d'administration des fiches d'affectation.</p>
    </div>
    <a href="{{ route('agents.create') }}" class="btn btn-green-primary d-flex align-items-center">
        <i class="fas fa-plus me-2"></i> Nouvel agent
    </a>
</div>

<div class="card custom-card mb-4">
    <div class="card-body p-3">
        <form action="{{ route('agents.index') }}" method="GET" class="row g-3">
            <div class="col-md-5 position-relative">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="form-control search-box" placeholder="Rechercher par nom, prénom ou matricule..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="structure" class="form-select select-custom">
                    <option value="">Toutes les UFR / Structures</option>
                    @foreach($structures ?? [] as $struct)
                        <option value="{{ $struct->code }}" {{ request('structure') == $struct->code ? 'selected' : '' }}>
                            {{ $struct->code }} - {{ $struct->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-green-primary flex-grow-1">Filtrer</button>
                <a href="{{ route('agents.index') }}" class="btn btn-outline-custom px-3 d-flex align-items-center justify-content-center" title="Réinitialiser"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card custom-card">
    <div class="card-body p-0">
        @if(isset($agents) && $agents->count() > 0)
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Matricule</th>
                        <th>Corps</th>
                        <th>Structure / UFR</th>
                        <th>Solde Restant</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agents as $agent)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="agent-avatar-text">
                                    {{ strtoupper(substr($agent->prenom, 0, 1) . substr($agent->nom, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="fw-600 text-dark d-block">{{ $agent->prenom }} {{ $agent->nom }}</span>
                                    <small class="text-muted" style="font-size: 0.78rem;">{{ $agent->email ?? 'Pas d\'adresse email' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-dark fw-500">{{ $agent->matricule }}</span></td>
                        <td><span class="badge {{ ($agent->type_personnel ?? $agent->statut) == 'PER' ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning-700' }} fw-600 px-2 py-1">{{ $agent->type_personnel ?? $agent->statut ?? 'PATS' }}</span></td>
                        <td><span class="badge-structure">{{ $agent->lieuAffectation->code ?? 'Non assigné' }}</span></td>
                        <td><span class="text-success fw-600">{{ $agent->conges_restants ?? 30 }} j</span></td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('agents.show', $agent->id) }}" class="btn btn-sm btn-outline-custom border-0"><i class="fas fa-eye text-primary"></i></a>
                                <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-outline-custom border-0"><i class="fas fa-edit text-warning"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-5 text-center text-muted">
            <div class="mb-3"><i class="fas fa-seedling text-success-subtle" style="font-size: 3rem;"></i></div>
            <h5 class="text-dark fw-600 mb-1">Aucun agent trouvé</h5>
            <p class="small mb-3">La base de données est vide. Remplissez des fiches pour commencer le suivi.</p>
            <a href="{{ route('agents.create') }}" class="btn btn-green-primary btn-sm">Créer un profil agent</a>
        </div>
        @endif
    </div>
</div>
@endsection
