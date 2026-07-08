@extends('layouts.app')
@section('title', 'Rapport ' . $lieu->nom)
@section('page-title', 'Rapport - ' . $lieu->nom)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">{{ $lieu->nom }}</h5>
        <small class="text-muted">Tableau des congés {{ $annee }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('rapports.pdf', $lieu) }}" class="btn btn-danger"><i class="fas fa-file-pdf me-2"></i>Exporter PDF</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary">Retour</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>N&deg;</th>
                    <th>Nom et Prénom</th>
                    <th>Matricule</th>
                    <th class="text-center">Congés Dus (j)</th>
                    <th class="text-center">À Prendre (j)</th>
                    <th class="text-center">Absences (j)</th>
                    <th class="text-center">Restants (j)</th>
                    <th>Date Cessation</th>
                    <th>Date Reprise</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $i => $agent)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-500">{{ $agent->nom_complet }}</td>
                    <td><code>{{ $agent->matricule_solde }}</code></td>
                    <td class="text-center fw-bold">{{ $agent->conges_dus }}</td>
                    <td class="text-center">{{ $agent->jours_a_prendre }}</td>
                    <td class="text-center">{{ $agent->absences_deductibles_annee }}</td>
                    <td class="text-center"><strong class="{{ $agent->jours_restants > 0 ? 'text-success' : 'text-muted' }}">{{ $agent->jours_restants }}</strong></td>
                    <td>{{ $agent->date_cessation ? \Carbon\Carbon::parse($agent->date_cessation)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $agent->date_reprise ? \Carbon\Carbon::parse($agent->date_reprise)->format('d/m/Y') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Aucun agent dans cette structure</td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="3" class="text-end">TOTAUX</td>
                    <td class="text-center">{{ $agents->sum(fn($a) => $a->conges_dus) }}</td>
                    <td class="text-center">{{ $agents->sum(fn($a) => $a->jours_a_prendre) }}</td>
                    <td class="text-center">{{ $agents->sum(fn($a) => $a->absences_deductibles_annee) }}</td>
                    <td class="text-center">{{ $agents->sum(fn($a) => $a->jours_restants) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
</div>
@endsection
