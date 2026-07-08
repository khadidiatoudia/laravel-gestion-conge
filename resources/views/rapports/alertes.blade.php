@extends('layouts.app')
@section('title', 'Soldes Critiques - ' . $lieu->nom)
@section('page-title', 'Soldes Critiques - ' . $lieu->nom)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">{{ $lieu->nom }}</h5>
        <small class="text-muted">Agents avec Soldes Critiques (≥ 48 jours) - {{ $annee }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('rapports.pdf', ['rapport' => 'alertes', 'lieu' => $lieu->nom]) }}" class="btn btn-danger"><i class="fas fa-file-pdf me-2"></i>Exporter PDF</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
</div>

<div class="alert alert-warning d-flex align-items-center" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <div>
        <strong>Attention !</strong> Les agents listés ci-dessous présentent des soldes de congés critiques (≥ 48 jours) et nécessitent une planification urgent des repos compensatoires.
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>N°</th>
                    <th>Nom et Prénom</th>
                    <th>Matricule</th>
                    <th class="text-center">Congés Dus (j)</th>
                    <th class="text-center">À Prendre (j)</th>
                    <th class="text-center">Restants (j)</th>
                    <th>Lieu Affectation</th>
                    <th>Observations</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $i => $agent)
                <tr class="{{ $agent->conges_dus >= 60 ? 'table-danger' : 'table-warning' }}">
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-500">{{ $agent->nom_complet }}</td>
                    <td><code>{{ $agent->matricule_solde }}</code></td>
                    <td class="text-center fw-bold">{{ $agent->conges_dus }}</td>
                    <td class="text-center">{{ $agent->jours_a_prendre }}</td>
                    <td class="text-center"><strong class="text-danger">{{ $agent->jours_restants }}</strong></td>
                    <td>{{ $agent->lieuAffectation?->nom ?? '-' }}</td>
                    <td>
                        @if($agent->conges_dus >= 60)
                            <span class="badge bg-danger">CRITIQUE</span>
                        @else
                            <span class="badge bg-warning">Élevé</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Aucun agent avec solde critique dans cette structure</td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="3" class="text-end">TOTAUX</td>
                    <td class="text-center">{{ $agents->sum(fn($a) => $a->conges_dus) }}</td>
                    <td class="text-center">{{ $agents->sum(fn($a) => $a->jours_a_prendre) }}</td>
                    <td class="text-center">{{ $agents->sum(fn($a) => $a->jours_restants) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
</div>
@endsection
