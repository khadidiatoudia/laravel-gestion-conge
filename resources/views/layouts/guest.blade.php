<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'USSEIN') - Gestion RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ussein-green: #0f5132;
            --ussein-green-light: #198754;
            --ussein-gold: #ffc107;
            --background: linear-gradient(135deg, #0b261c 0%, #113b2a 50%, #0f5132 100%);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            margin: 0;
            background: var(--background);
            color: #f8fafc;
        }
        .guest-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 32px 24px;
        }
        .guest-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }
        .guest-brand {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: #ffffff;
        }
        .brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: rgba(255,255,255,.12);
            display: grid;
            place-items: center;
            color: var(--ussein-gold);
            font-size: 1.3rem;
        }
        .brand-text h1 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -.03em;
        }
        .brand-text p {
            margin: 0;
            color: rgba(255,255,255,.75);
            font-size: .9rem;
        }
        .guest-actions a {
            font-weight: 700;
        }
        .btn-guest-primary {
            background: var(--ussein-gold);
            color: #0f172a !important;
            border: none;
        }
        .btn-guest-primary:hover {
            background: #d6a100;
        }
        .guest-card {
            flex: 1;
            max-width: 520px;
            width: 100%;
            margin: 0 auto;
            border-radius: 24px;
            overflow: hidden;
            background: rgba(255,255,255,.08);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,.14);
            box-shadow: 0 24px 80px rgba(0,0,0,.25);
        }
        .guest-card-body {
            padding: 34px 30px;
        }
        .guest-card h2 {
            font-weight: 800;
            margin-bottom: 8px;
            color: #ffffff;
        }
        .guest-card p.lead {
            color: rgba(255,255,255,.75);
            margin-bottom: 28px;
        }
        .form-control {
            background: rgba(255,255,255,.12) !important;
            border: 1px solid rgba(255,255,255,.16) !important;
            color: #ffffff !important;
            border-radius: 14px;
            height: 52px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.15rem rgba(255, 193, 7, 0.2);
            border-color: rgba(255,255,255,.5) !important;
            background: rgba(255,255,255,.17) !important;
        }
        .form-label {
            color: rgba(255,255,255,.85);
            font-weight: 600;
        }
        .invalid-feedback { color: #f8d7da; }
        .alert {
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,.18);
            background: rgba(0,0,0,.25);
            color: #ffe4e6;
        }
        .back-link {
            color: rgba(255,255,255,.82);
            font-weight: 600;
            text-decoration: none;
        }
        .back-link:hover {
            color: #ffffff;
        }
        @media (max-width: 760px) {
            .guest-header { justify-content: center; text-align: center; }
            .guest-actions { width: 100%; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="guest-shell">
    <header class="guest-header">
        <a href="{{ route('welcome') }}" class="guest-brand">
            <span class="brand-icon"><i class="fas fa-seedling"></i></span>
            <div class="brand-text">
                <h1>USSEIN</h1>
                <p>Gestion des congés & absences</p>
            </div>
        </a>

        <div class="guest-actions">
            <a href="{{ route('login') }}" class="btn btn-outline-light">Connexion</a>
            <a href="{{ route('register') }}" class="btn btn-guest-primary">Créer un compte</a>
        </div>
    </header>

    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
