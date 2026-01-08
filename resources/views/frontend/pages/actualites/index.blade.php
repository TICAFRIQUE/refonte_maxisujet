@extends('frontend.layouts.front_app')

@section('title', 'Actualités Éducatives - MaxiSujets')
@section('meta_description', 'Découvrez les dernières actualités du monde éducatif en Côte d\'Ivoire. Réformes,
    innovations, conseils et nouvelles du secteur de l\'éducation.')

@section('content')
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light rounded p-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}" class="text-decoration-none">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active">Actualités</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-dark">📰 Actualités Éducatives</h1>
                    <p class="lead text-muted">Restez informé des dernières nouvelles du secteur éducatif</p>
                </div>
            </div>
        </div>



        <!-- Barre de recherche -->
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" action="{{ route('actualites.index') }}" class="d-flex">
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Rechercher dans les actualités..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Actualités en vedette -->
        @if ($actualitesFeatured->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="h4 mb-4 fw-bold">🌟 À la Une</h3>
                    <div class="row g-4">
                        @foreach ($actualitesFeatured as $actualite)
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                                    @if ($actualite->getFirstMediaUrl('image_principale'))
                                        <img src="{{ $actualite->getFirstMediaUrl('image_principale', 'medium') }}"
                                            class="card-img-top" alt="{{ $actualite->titre }}"
                                            style="height: 220px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient"
                                            style="height: 220px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="bi bi-newspaper text-white" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge bg-danger rounded-pill">
                                                <i class="bi bi-star me-1"></i>En vedette
                                            </span>
                                            <small class="text-muted">
                                                {{ $actualite->date_publication ? $actualite->date_publication->format('d M Y') : $actualite->created_at->format('d M Y') }}
                                            </small>
                                        </div>
                                        <h5 class="card-title fw-bold mb-3">{{ $actualite->titre }}</h5>
                                        @if ($actualite->resume)
                                            <p class="card-text text-muted mb-3">{{ Str::limit($actualite->resume, 120) }}
                                            </p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('rubrique.show', $actualite->slug) }}"
                                                class="btn btn-primary rounded-pill px-4">
                                                <i class="bi bi-arrow-right me-1"></i>Lire l'article
                                            </a>
                                            <small class="text-muted">
                                                <i class="bi bi-eye me-1"></i>{{ $actualite->nb_vues }} vues
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Liste des actualités -->
        <div class="row">
            <div class="col-12">
                <h3 class="h4 mb-4 fw-bold">Toutes les actualités</h3>
                @if ($actualites->count() > 0)
                    <div class="row g-4">
                        @foreach ($actualites as $actualite)
                            <div class="col-lg-6 col-md-6">
                                <div class="card h-100 border-0 shadow-sm"
                                    style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-5px)'"
                                    onmouseout="this.style.transform='translateY(0)'">
                                    <div class="row g-0 h-100">
                                        <div class="col-4">
                                            @if ($actualite->getFirstMediaUrl('image_principale'))
                                                <img src="{{ $actualite->getFirstMediaUrl('image_principale', 'thumb') }}"
                                                    class="img-fluid h-100" alt="{{ $actualite->titre }}"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="h-100 d-flex align-items-center justify-content-center bg-gradient"
                                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <i class="bi bi-newspaper text-white" style="font-size: 2rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-3 h-100 d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge bg-primary rounded-pill small">Actualité</span>
                                                    <small class="text-muted">
                                                        {{ $actualite->date_publication ? $actualite->date_publication->format('d/m/Y') : $actualite->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <h6 class="card-title fw-bold mb-2">{{ Str::limit($actualite->titre, 60) }}
                                                </h6>
                                                @if ($actualite->resume)
                                                    <p class="card-text text-muted small mb-3" style="flex-grow: 1;">
                                                        {{ Str::limit($actualite->resume, 80) }}
                                                    </p>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                                    <a href="{{ route('rubrique.show', $actualite->slug) }}"
                                                        class="btn btn-outline-primary btn-sm rounded-pill">
                                                        Lire plus
                                                    </a>
                                                    <small class="text-muted">
                                                        <i class="bi bi-eye"></i> {{ $actualite->nb_vues }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-5">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $actualites->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-newspaper display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">Aucune actualité trouvée</h4>
                        @if (request('search'))
                            <p class="text-muted">Aucun résultat pour "{{ request('search') }}"</p>
                            <a href="{{ route('actualites.index') }}" class="btn btn-primary">Voir toutes les
                                actualités</a>
                        @else
                            <p class="text-muted">Les actualités apparaîtront ici prochainement.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
