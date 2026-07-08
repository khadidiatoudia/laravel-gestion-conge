@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
<div class="guest-card">
    <div class="guest-card-body">
        <h2>Bienvenue</h2>
        <p class="lead">Connectez-vous pour accéder à votre espace de gestion des congés et absences.</p>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Adresse Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="votre@email.sn" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-guest-primary w-100 py-3 mt-2">
                <i class="fas fa-sign-in-alt me-2"></i> Se connecter
            </button>
        </form>

        <div class="text-center mt-4">
            <span class="d-block text-white-75 mb-2">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" class="btn btn-outline-light w-100 py-3">
                <i class="fas fa-user-plus me-2"></i> Créer un compte
            </a>
        </div>
    </div>
</div>
@endsection
