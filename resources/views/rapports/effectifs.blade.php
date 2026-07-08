@extends('layouts.app')
@section('title', 'Registre Matriculaire - ' . $lieu->nom)
@section('page-title', 'Registre Matriculaire - ' . $lieu->nom)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">{{ $lieu->nom }}</h5>
        <small class="text-muted">Registre Matriculaire {{ $annee }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('rapports.pdf', ['rapport' => 'effectifs', 'lieu' => $lieu->nom]) }}" class="btn btn-danger"><i class="fas fa-file-pdf me-2"></i>Exporter PDF</a>
        <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary">Retour</a>
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
                    <th>Sexe</th>
                    <th>Nombre d'Enfants</th>
                    <th>Date Prise Service</th>
                    <th>Lieu Affectation</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agents as $i => $agent)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-500">{{ $agent->nom_complet }}</td>
                    <td><code>{{ $agent->matricule_solde }}</code></td>
                    <td class="text-center">{{ $agent->sexe === 'M' ? 'Masculin' : 'Féminin' }}</td>
                    <td class="text-center">{{ $agent->nombre_enfants }}</td>
                    <td>{{ $agent->date_prise_service->format('d/m/Y') }}</td>
                    <td>{{ $agent->lieuAffectation?->nom ?? '-' }}</td>
                    <td>
                        @if($agent->actif)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Aucun agent dans cette structure</td></tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="2" class="text-end">TOTAL</td>
                    <td colspan="6" class="text-start">{{ count($agents) }} agent(s)</td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
</div>
@endsection
