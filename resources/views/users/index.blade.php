@extends('layouts.app')
@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Liste des utilisateurs</h5>
                    <small class="text-muted">Rechercher un email ou un nom pour associer un agent.</small>
                </div>
                <form class="d-flex" method="GET" action="{{ route('users.index') }}">
                    <input type="search" name="search" class="form-control form-control-sm me-2" placeholder="Recherche..." value="{{ request('search') }}">
                    <button class="btn btn-sm btn-primary">Rechercher</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Agent lié</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>{{ $user->agent?->nom_complet ?? 'Aucun' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun utilisateur trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
