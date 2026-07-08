<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Congés') - USSEIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 270px;
            --primary: #16a34a;       /* Vert emblématique USSEIN */
            --primary-dark: #14532d;  /* Vert forêt profond */
            --sidebar-bg: #0f2d24;    /* Vert très sombre pour un contraste maximal */
            --slate-700: #334155;
            --slate-400: #94a3b8;
        }
        body { background: #f1f5f9; font-family: 'Segoe UI', system-ui, sans-serif; color: #1e293b; }

        /* Sidebar Ergonomique Haute Visibilité */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            box-shadow: 4px 0 25px rgba(0,0,0,.08);
        }
        .sidebar .brand { background: rgba(0,0,0,.2); padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,.05); }
        .sidebar .brand h5 { color: #ffffff; font-weight: 700; font-size: 1.15rem; margin: 0; letter-spacing: 0.5px; }
        .sidebar .brand small { color: rgba(255, 255, 255, 0.6); font-size: .75rem; display: block; margin-top: 4px; }

        /* Titres de sections enfin lisibles */
        .sidebar .nav-section {
            color: #86efac !important; /* Vert menthe clair pour casser le sombre */
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 24px 20px 8px;
            font-weight: 700;
            opacity: 0.85;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important; /* Plus blanc pour le contraste */
            padding: 12px 20px;
            font-size: .92rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all .2s ease;
            border-left: 4px solid transparent;
        }
        .sidebar .nav-link i {
            color: rgba(255, 255, 255, 0.4);
            font-size: 1rem;
            transition: .2s;
        }
        .sidebar .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255,255,255,.05);
        }
        .sidebar .nav-link.active {
            color: #ffffff !important;
            background: rgba(22, 163, 74, 0.15);
            border-left: 4px solid var(--primary);
            font-weight: 600;
        }
        .sidebar .nav-link.active i {
            color: var(--primary);
        }

        /* Contenu Principal */
        .main-content { margin-left: var(--sidebar-width); padding: 0; }
        .topbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .content-area { padding: 32px; }

        /* Éléments globaux */
        .card { border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.02); background: #ffffff; }

        /* Bouton Déconnexion plus discret mais accessible */
        .btn-logout {
            color: #64748b;
            background: #f1f5f9;
            border: none;
            padding: 7px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 6px;
            transition: .2s;
        }
        .btn-logout:hover {
            color: #b91c1c;
            background: #fef2f2;
        }

        @media(max-width: 768px) { .sidebar { width: 0; overflow: hidden; } .main-content { margin-left: 0; } }
    </style>
    @yield('styles')
</head>
<body>
@auth
<div class="sidebar">
    <div class="brand">
        <h5><i class="fas fa-seedling me-2" style="color: #4ade80;"></i>USSEIN</h5>
        <small>Gestion des Congés &amp; Absences</small>
    </div>
    <nav class="mt-2">
        <div class="nav-section">Principal</div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie fa-fw"></i> Tableau de bord
            </a>
            <div class="nav-section">Gestion RH</div>
            <a href="{{ route('agents.index') }}" class="nav-link {{ request()->routeIs('agents.*') ? 'active' : '' }}">
                <i class="fas fa-user-friends fa-fw"></i> Gestion du Personnel
            </a>
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-user fa-fw"></i> Gestion des Utilisateurs
            </a>
            <div class="nav-section">Rapports &amp; Outils</div>
            <a href="{{ route('rapports.index') }}" class="nav-link {{ request()->routeIs('rapports.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice fa-fw"></i> Rapports Administratifs
            </a>
            <div class="nav-section">Configuration</div>
            <a href="{{ route('jours_feries.index') }}" class="nav-link {{ request()->routeIs('jours_feries.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt fa-fw"></i> Calendrier RH
            </a>
        @else
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie fa-fw"></i> Mon tableau de bord
            </a>
        @endif
    </nav>
    <div class="position-absolute bottom-0 w-100 p-3" style="border-top: 1px solid rgba(255,255,255,.05); background: rgba(0,0,0,0.15);">
        <small class="text-white d-block fw-600"><i class="fas fa-user-shield me-1" style="color: #4ade80;"></i> {{ auth()->user()->name }}</small>
        <small class="text-white-50" style="font-size: 0.75rem;">{{ auth()->user()->role === 'admin' ? 'Administrateur DRH' : 'Gestionnaire' }}</small>
    </div>
</div>
@endauth

<div class="main-content" @auth style="margin-left: var(--sidebar-width);" @endauth>
    @auth
    <div class="topbar">
        <h5 class="mb-0 fw-700 text-dark" style="letter-spacing: -0.5px;">@yield('page-title', 'Tableau de bord')</h5>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small fw-600"><i class="far fa-calendar-alt me-1 text-success"></i> {{ now()->format('d/m/Y') }}</span>
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                </button>
            </form>
        </div>
    </div>
    @endauth

    <div class="content-area">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
