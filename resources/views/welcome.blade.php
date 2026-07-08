<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>USSEIN - Gestion des Congés & Absences</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --ussein-green: #0f5132;
            --ussein-green-light: #198754;
            --ussein-gold: #ffc107;
            --ussein-gold-dark: #e0a800;
            --dark-blue: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
        }

        /* NAVBAR PREMIUM */
        .custom-navbar {
            background: #ffffff;
            padding: 12px 0; /* Un poil plus compact pour équilibrer le logo */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border-bottom: 3px solid var(--ussein-green);
        }

        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        /* Conteneur optimisé pour accueillir le vrai logo image */
        .logo-image-container {
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-image-container img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }

        .brand-text h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--ussein-green);
            margin: 0;
            line-height: 1.1;
        }

        .brand-text span {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--ussein-green-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-connect {
            background-color: var(--ussein-green);
            color: white !important;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-connect:hover {
            background-color: #0b3d26;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(15, 81, 50, 0.2);
        }

        .btn-connect-secondary {
            background: transparent;
            color: var(--ussein-green) !important;
            border: 1px solid var(--ussein-green);
        }

        .btn-connect-secondary:hover {
            background: rgba(25, 135, 84, 0.08);
            color: #0b3d26 !important;
        }

        .hero-list {
            margin: 2rem 0 0;
            padding: 0;
            list-style: none;
            max-width: 560px;
        }

        .hero-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
            color: #e2e8f0;
            font-size: 1rem;
            line-height: 1.6;
        }

        .hero-list li i {
            margin-top: 4px;
            color: var(--ussein-gold);
        }

        /* HERO BANNER */
        .hero-banner {
            background: linear-gradient(135deg, #0a3622 0%, #144d32 100%);
            padding: 90px 0 120px 0;
            color: white;
            position: relative;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: #f8fafc;
            clip-path: polygon(0 100%, 100% 100%, 100% 0);
        }

        .badge-status {
            background: rgba(255, 193, 7, 0.15);
            color: var(--ussein-gold);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.2;
            margin-top: 15px;
        }

        .hero-title span {
            color: var(--ussein-gold);
        }

        .hero-description {
            color: #cbd5e1;
            font-size: 1.1rem;
            line-height: 1.6;
            max-width: 580px;
        }

        .btn-gold-action {
            background-color: var(--ussein-gold);
            color: var(--dark-blue) !important;
            font-weight: 700;
            padding: 14px 32px;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .btn-gold-action:hover {
            background-color: var(--ussein-gold-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 193, 7, 0.4);
        }

        /* SIDE PANEL STATS */
        .stats-panel {
            background: white;
            border-radius: 20px;
            padding: 30px;
            color: #1e293b;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-top: 5px solid var(--ussein-gold);
        }

        .struct-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 12px;
        }

        .struct-item i {
            color: var(--ussein-green-light);
        }

        /* SERVICE CARDS SECTION */
        .services-container {
            margin-top: -50px;
            position: relative;
            z-index: 10;
            padding-bottom: 60px;
        }

        .card-service {
            background: white;
            border-radius: 16px;
            padding: 35px 25px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .card-service:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(15, 81, 50, 0.08);
            border-color: var(--ussein-green-light);
        }

        .card-icon-wrap {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 24px;
        }

        .bg-green-light { background-color: rgba(25, 135, 84, 0.1); color: var(--ussein-green-light); }
        .bg-gold-light { background-color: rgba(255, 193, 7, 0.15); color: #b45309; }
        .bg-teal-light { background-color: rgba(13, 148, 136, 0.1); color: #0d9488; }

        .card-service h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .card-service p {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.5;
            margin: 0;
        }

        /* FOOTER */
        footer {
            background-color: var(--dark-blue);
            color: #94a3b8;
            padding: 30px 0;
            font-size: 0.9rem;
            border-top: 4px solid var(--ussein-green);
        }
    </style>
</head>
<body>

    <nav class="custom-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ route('welcome') }}" class="brand-logo-container">
                <div class="logo-image-container">
                    <img src="{{ asset('https://tse3.mm.bing.net/th/id/OIP.UslaffqWpICCIBRGNVr4sgAAAA?rs=1&pid=ImgDetMain&o=7&rm=3') }}" alt="Logo USSEIN">
                </div>
                <div class="brand-text">
                    <h1>USSEIN</h1>
                    <span>Université du Sine Saloum El-Hâdj Ibrahima Niass</span>
                </div>
            </a>

            <div class="d-flex flex-wrap gap-2">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="btn-connect">
                        <i class="fas fa-th-large"></i> Tableau de bord
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-connect">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </a>
                    <a href="{{ route('register') }}" class="btn-connect btn-connect-secondary">
                        <i class="fas fa-user-plus"></i> Créer un compte
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="hero-banner">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 text-center text-lg-start">
                    <div class="badge-status">
                        <i class="fas fa-shield-alt"></i> Système RH Centralisé
                    </div>
                    <h2 class="hero-title">
                        Gestion Intégrée des <br><span>Congés & Absences</span>
                    </h2>
                    <p class="hero-description my-4">
                        Optimisez la gestion des congés, des absences et des droits de chaque agent avec une plateforme dédiée aux besoins des UFR, directions et services centraux.
                    </p>
                    <ul class="hero-list">
                        <li><i class="fas fa-check-circle"></i> Calcul automatique des congés dus, des absences et des jours restants.</li>
                        <li><i class="fas fa-check-circle"></i> Gestion des demandes de congés, avec date de reprise calculée sur jours ouvrables.</li>
                        <li><i class="fas fa-check-circle"></i> Rapports exportables par direction, UFR, Rectorat et Vice-rectorat.</li>
                    </ul>
                    <div class="mt-4 d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="btn-gold-action">
                                <i class="fas fa-door-open"></i> Accéder à mon espace
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-gold-action">
                                <i class="fas fa-lock"></i> Se connecter
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg text-white border-white border-opacity-25">
                                <i class="fas fa-user-plus"></i> Créer un compte
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <div class="stats-panel">
                        <h4 class="fw-bold mb-3" style="font-size: 1.1rem; color: var(--ussein-green);">
                            <i class="fas fa-map-marker-alt me-2"></i>Pôles & Affectations
                        </h4>
                        <p class="text-muted small mb-4">Périmètre d'application des ressources humaines de l'USSEIN :</p>

                        <div class="struct-item"><i class="fas fa-check-circle"></i> 4 UFR Académiques Spécialisées</div>
                        <div class="struct-item"><i class="fas fa-check-circle"></i> Rectorat & Services Centraux</div>
                        <div class="struct-item"><i class="fas fa-check-circle"></i> Directions Administratives</div>

                        <hr class="my-4" style="opacity: 0.15;">

                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <span class="d-block h4 fw-bold text-success mb-0">PER</span>
                                    <small class="text-muted fw-medium" style="font-size: 11px;">Enseignants</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <span class="d-block h4 fw-bold text-primary mb-0">PATS</span>
                                    <small class="text-muted fw-medium" style="font-size: 11px;">Administratifs</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="services-container">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="card-service">
                        <div class="card-icon-wrap bg-green-light">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3>Suivi des Congés</h3>
                        <p>Suivi en temps réel et décompte automatisé des droits aux congés annuels cumulés, consommés et restants pour chaque agent.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card-service">
                        <div class="card-icon-wrap bg-gold-light">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Contrôle des Absences</h3>
                        <p>Cartographie instantanée des absences exceptionnelles, autorisations de congé et régularisations soumises par structure.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card-service">
                        <div class="card-icon-wrap bg-teal-light">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h3>Rapports Décisionnels</h3>
                        <p>Génération de bilans périodiques complets et exports au format PDF par UFR ou par direction pour optimiser le pilotage académique.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center">
        <div class="container">
            <p class="mb-1 fw-bold text-white">USSEIN — Direction des Ressources Humaines</p>
            <p class="mb-0 text-muted" style="font-size: 0.8rem;">&copy; 2026 Université du Sine Saloum El-Hâdj Ibrahima Niass. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
