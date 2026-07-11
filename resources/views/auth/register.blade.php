@extends('layouts.guest')
@section('title', 'Inscription')

@section('content')
<div class="guest-card">
    <div class="guest-card-body">
        <h2>Créer un compte</h2>
        <p class="lead">Inscrivez-vous pour gérer vos congés, consulter vos absences et suivre votre solde.</p>

        @if(session('success'))
            <div class="alert alert-success mb-3" style="background:rgba(22,163,74,.15);border-color:rgba(22,163,74,.3);color:#bbf7d0;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-3" style="background:rgba(220,38,38,.15);border-color:rgba(220,38,38,.3);color:#fecaca;">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nom complet</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmation du mot de passe</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Matricule de solde (optionnel)</label>
                <input type="text" name="matricule_solde" class="form-control @error('matricule_solde') is-invalid @enderror" value="{{ old('matricule_solde') }}" placeholder="Ex: USS-2026-X">
                <small class="form-text text-muted text-white-75">Permet d'associer votre compte à votre profil agent si vous en avez déjà un.</small>
                @error('matricule_solde')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-guest-primary w-100 py-3 mt-2">
                <i class="fas fa-user-plus me-2"></i> Créer mon compte
            </button>
        </form>

        <div class="text-center mt-4">
            <span class="d-block text-white-75 mb-2">Déjà inscrit ?</span>
            <a href="{{ route('login') }}" class="btn btn-outline-light w-100 py-3">
                <i class="fas fa-sign-in-alt me-2"></i> Se connecter
            </a>
        </div>
    </div>
</div>
@endsection
