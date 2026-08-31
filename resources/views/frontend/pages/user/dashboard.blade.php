@extends('frontend.layouts.front_app')

@section('title', 'Tableau de bord - MaxiSujets')

@section('content')
    @push('styles')
        <style>
            .account-nav {
                display: flex;
                gap: 0.5rem;
                border-bottom: 1px solid var(--ms-border);
                margin-bottom: 2rem;
                overflow-x: auto;
            }

            .account-nav .nav-link {
                color: var(--ms-muted);
                font-weight: 600;
                padding: 0.75rem 1rem;
                border-bottom: 3px solid transparent;
                white-space: nowrap;
            }

            .account-nav .nav-link.active {
                color: var(--ms-blue);
                border-bottom-color: var(--ms-blue);
            }

            .dash-stat-icon {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .bareme-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 0;
                border-bottom: 1px solid var(--ms-border-subtle);
            }

            .bareme-item:last-child { border-bottom: none; }
        </style>
    @endpush

    <div class="container mt-4">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            @include('frontend.components.retour')
        <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
            <ol class="breadcrumb bg-light rounded p-3 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('accueil') }}" class="text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
            </ol>
        </nav>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 mb-4 gap-2">
            <div>
                <h1 class="fw-bold mb-1">Bonjour {{ $user->username }}</h1>
                <p class="text-muted mb-0">Voici un résumé de votre activité sur MaxiSujets.</p>
            </div>
            <span class="points-pill">
                <i class="bi bi-star-fill"></i> {{ $points }} point{{ $points > 1 ? 's' : '' }}
            </span>
        </div>

        <!-- Navigation de compte : ancres simples, pas de JS -->
        <div class="account-nav">
            <a class="nav-link" href="#section-profil">Profil</a>
            <a class="nav-link" href="#section-telechargements">Téléchargements</a>
            <a class="nav-link" href="{{ route('user.sujet.index') }}">Mes sujets</a>
        </div>

        <!-- Statistiques -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="dash-stat-icon mb-3 mx-auto" style="background: var(--ms-blue-light);">
                            <i class="bi bi-download" style="font-size: 1.5rem; color: var(--ms-blue);"></i>
                        </div>
                        <h3 class="fw-bold mb-1" style="color: var(--ms-blue);">{{ $downloadsCount }}</h3>
                        <p class="text-muted mb-0 small">Téléchargements</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="dash-stat-icon mb-3 mx-auto" style="background: var(--ms-orange-light);">
                            <i class="bi bi-file-earmark-text" style="font-size: 1.5rem; color: var(--ms-orange);"></i>
                        </div>
                        <h3 class="fw-bold mb-1" style="color: var(--ms-orange-dark);">{{ $publishedSubjectsCount }}</h3>
                        <p class="text-muted mb-0 small">Sujets publiés</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="dash-stat-icon mb-3 mx-auto" style="background: #fff4e0;">
                            <i class="bi bi-star-fill" style="font-size: 1.5rem; color: #d97706;"></i>
                        </div>
                        <h3 class="fw-bold mb-1" style="color: #d97706;">{{ $points }}</h3>
                        <p class="text-muted mb-0 small">Solde de points</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Barème de points -->
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2" style="color: var(--ms-blue);"></i>Comment gagner des points ?</h5>
                        <div class="bareme-item">
                            <span><i class="bi bi-person-plus me-2 text-muted"></i>Inscription</span>
                            <strong style="color: var(--ms-orange-dark);">+50</strong>
                        </div>
                        <div class="bareme-item">
                            <span><i class="bi bi-calendar-check me-2 text-muted"></i>Connexion (tous les 2 jours)</span>
                            <strong style="color: var(--ms-orange-dark);">+10</strong>
                        </div>
                        <div class="bareme-item">
                            <span><i class="bi bi-cloud-upload me-2 text-muted"></i>Publication d'un sujet approuvé</span>
                            <strong style="color: var(--ms-orange-dark);">+100</strong>
                        </div>
                        <div class="bareme-item">
                            <span><i class="bi bi-download me-2 text-muted"></i>Téléchargement d'un sujet</span>
                            <strong class="text-danger">-1</strong>
                        </div>
                        <a href="{{ route('user.sujet.create') }}" class="btn btn-warning w-100 mt-3">
                            <i class="bi bi-plus-circle me-2"></i>Publier un sujet maintenant
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mes derniers sujets -->
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-text me-2" style="color: var(--ms-orange);"></i>Mes derniers sujets</h5>
                            <a href="{{ route('user.sujet.index') }}" class="small text-decoration-none fw-semibold">Voir tout <i class="bi bi-arrow-right"></i></a>
                        </div>
                        @if ($mySujets->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
                                <p class="text-muted mb-0 mt-2">Vous n'avez pas encore publié de sujet.</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach ($mySujets as $sujet)
                                    <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1 me-3">
                                            <h6 class="mb-1 fw-semibold">{{ Str::limit($sujet->libelle, 40) }}</h6>
                                            <small class="text-muted">{{ $sujet->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        @if ($sujet->approuve)
                                            <span class="status-badge status-approved"><i class="bi bi-check-circle-fill"></i> Approuvé</span>
                                        @else
                                            <span class="status-badge status-pending"><i class="bi bi-hourglass-split"></i> En attente</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profil et téléchargements : sections empilées (pas d'onglets JS, toujours visibles) -->
        <div>
            <div id="section-profil" style="scroll-margin-top: 100px;">
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h4 class="mb-1 fw-bold">Mon profil</h4>
                        <p class="text-muted mb-4">Gérez vos informations personnelles</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Erreurs de validation :</strong>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('user.profile') }}" class="row g-3" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">Nom d'utilisateur</label>
                                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">Téléphone</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label for="profil" class="form-label fw-semibold">Profil</label>
                                <select name="profil" id="profil" class="form-select" required>
                                    <option value="eleve" {{ $user->profil == 'eleve' ? 'selected' : '' }}>Élève</option>
                                    <option value="etudiant" {{ $user->profil == 'etudiant' ? 'selected' : '' }}>Étudiant(e)</option>
                                    <option value="enseignant" {{ $user->profil == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                                    <option value="parent" {{ $user->profil == 'parent' ? 'selected' : '' }}>Parent</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label fw-semibold">
                                    Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small>
                                </label>
                                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" placeholder="••••••••">
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-2"></i>Mettre à jour le profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="section-telechargements" style="scroll-margin-top: 100px;">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="mb-1 fw-bold">Historique des téléchargements</h4>
                        <p class="text-muted mb-4">L'ensemble de vos téléchargements récents</p>

                        @if ($downloads->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3">Aucun téléchargement</h6>
                                <p class="text-muted small mb-0">Vous n'avez téléchargé aucun sujet pour le moment.</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach ($downloads as $download)
                                    <div class="list-group-item border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1 me-3">
                                            <h6 class="mb-1 fw-semibold">{{ $download->sujet->libelle ?? 'Sujet inconnu' }}</h6>
                                            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $download->created_at->format('d/m/Y à H:i') }}</small>
                                        </div>
                                        @if ($download->sujet)
                                            <a href="{{ route('sujet.front.show', $download->sujet->libelle) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>Voir
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if ($downloads->hasPages())
                                <div class="mt-4">{{ $downloads->links() }}</div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
