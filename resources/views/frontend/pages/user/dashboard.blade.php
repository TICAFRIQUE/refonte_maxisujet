@extends('frontend.layouts.front_app')

@section('content')
    <div class="container-fluid py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 200px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="text-white mb-2 fw-bold">
                        <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                    </h1>
                    <p class="text-white-50 mb-0">Bienvenue {{ $user->username }}, gérez vos activités et profil</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-white bg-opacity-25 rounded-pill px-4 py-2 d-inline-block">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        <span class="text-white fw-bold">{{ $user->points ?? 0 }} points</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <!-- Breadcrumb moderne -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light rounded-pill shadow-sm px-4 py-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-house-door me-1"></i>Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
            </ol>
        </nav>

        <!-- Statistiques avec design moderne -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 70px; height: 70px;">
                                <i class="bi bi-download text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-success mb-2">{{ $downloadsCount }}</h3>
                        <h6 class="text-muted mb-0">Téléchargements</h6>
                        <small class="text-muted">Total des sujets téléchargés</small>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <i class="bi bi-arrow-up-right text-success opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 70px; height: 70px;">
                                <i class="bi bi-file-earmark-text text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-info mb-2">{{ $publishedSubjectsCount }}</h3>
                        <h6 class="text-muted mb-0">Sujets publiés</h6>
                        <small class="text-muted">Vos contributions</small>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <i class="bi bi-arrow-up-right text-info opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 70px; height: 70px;">
                                <i class="bi bi-star-fill text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-warning mb-2">{{ $user->points ?? 0 }}</h3>
                        <h6 class="text-muted mb-0">Points</h6>
                        <small class="text-muted">Votre solde actuel</small>
                    </div>
                    <div class="position-absolute top-0 end-0 m-3">
                        <i class="bi bi-arrow-up-right text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Section d'encouragement moderne -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-5 text-white text-center">
                        <div class="mb-4">
                            <i class="bi bi-people-fill" style="font-size: 4rem; opacity: 0.8;"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Participez à la communauté MaxiSujets !</h3>
                        <p class="lead mb-4 opacity-90">
                            En publiant des sujets, vous contribuez à enrichir notre plateforme et à aider d'autres
                            utilisateurs. De plus, chaque sujet que vous partagez vous permet de gagner des points, que vous
                            pourrez utiliser pour télécharger des ressources exclusives.
                        </p>
                        <a href="{{ route('user.sujet.create') }}" class="btn btn-warning btn-lg rounded-pill px-5 py-3 fw-bold">
                            <i class="bi bi-plus-circle me-2"></i>Publier un sujet maintenant
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal avec layout amélioré -->
        <div class="row g-4">
            <!-- Section Profil -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-white border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold text-primary">
                                    <i class="bi bi-person-circle me-2"></i>Mon profil
                                </h4>
                                <small class="text-muted">Gérez vos informations personnelles</small>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-pill px-3 py-2">
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                <span class="fw-bold text-primary">{{ $user->points ?? 0 }} points</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                    <strong>Erreurs de validation :</strong>
                                </div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('user.profile') }}" class="row g-4" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-semibold">
                                    <i class="bi bi-person me-1 text-primary"></i>Nom d'utilisateur
                                </label>
                                <input type="text" name="username" id="username" 
                                       class="form-control form-control-lg rounded-3 border-2" 
                                       value="{{ $user->username }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-1 text-primary"></i>Email
                                </label>
                                <input type="email" name="email" id="email" 
                                       class="form-control form-control-lg rounded-3 border-2" 
                                       value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1 text-primary"></i>Téléphone
                                </label>
                                <input type="text" name="phone" id="phone" 
                                       class="form-control form-control-lg rounded-3 border-2" 
                                       value="{{ $user->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label for="profil" class="form-label fw-semibold">
                                    <i class="bi bi-badge-tm me-1 text-primary"></i>Profil
                                </label>
                                <select name="profil" id="profil" class="form-select form-select-lg rounded-3 border-2" required>
                                    <option value="eleve" {{ $user->profil == 'eleve' ? 'selected' : '' }}>
                                        <i class="bi bi-mortarboard"></i> Élève
                                    </option>
                                    <option value="etudiant" {{ $user->profil == 'etudiant' ? 'selected' : '' }}>
                                        Étudiant(e)
                                    </option>
                                    <option value="enseignant" {{ $user->profil == 'enseignant' ? 'selected' : '' }}>
                                        Enseignant
                                    </option>
                                    <option value="parent" {{ $user->profil == 'parent' ? 'selected' : '' }}>
                                        Parent
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-1 text-primary"></i>Nouveau mot de passe
                                    <small class="text-muted ms-2">(laisser vide pour ne pas changer)</small>
                                </label>
                                <input type="password" name="password" id="password" 
                                       class="form-control form-control-lg rounded-3 border-2" 
                                       autocomplete="new-password" placeholder="••••••••">
                            </div>
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold">
                                        <i class="bi bi-check-circle me-2"></i>Mettre à jour le profil
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Section Historique -->
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-header bg-white border-0 p-4">
                        <h4 class="mb-1 fw-bold text-info">
                            <i class="bi bi-clock-history me-2"></i>Mes téléchargements
                        </h4>
                        <small class="text-muted">Historique de vos téléchargements récents</small>
                    </div>
                    <div class="card-body p-4">
                        @if ($downloads->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h6 class="text-muted">Aucun téléchargement</h6>
                                <p class="text-muted small mb-0">Vous n'avez téléchargé aucun sujet pour le moment.</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach ($downloads as $download)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="d-flex w-100 justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1 me-3">
                                                <h6 class="mb-1 fw-semibold text-truncate">
                                                    {{ $download->sujet->libelle ?? 'Sujet inconnu' }}
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $download->created_at->format('d/m/Y à H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <a href="{{ route('sujet.front.show', $download->sujet->libelle) }}" 
                                               class="btn btn-outline-info btn-sm rounded-pill">
                                                <i class="bi bi-eye me-1"></i>Voir
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($downloads->hasPages())
                                <div class="mt-4">
                                    {{ $downloads->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
