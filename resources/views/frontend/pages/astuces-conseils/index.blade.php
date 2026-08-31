@extends('frontend.layouts.front_app')

@section('title', 'Astuces & Conseils pour Réussir - MaxiSujets')
@section('meta_description', 'Découvrez nos meilleurs conseils et astuces pour réussir vos études. Méthodes de révision,
    gestion du stress, organisation du travail.')

@section('content')
    <div class="container">
        <!-- Breadcrumb -->
        @include('frontend.components.retour')
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light rounded p-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active">Astuces & Conseils</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold">Astuces & Conseils</h1>
                    <p class="lead text-muted">Nos meilleurs conseils pour vous accompagner vers la réussite</p>
                </div>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" action="{{ route('astuces-conseils.index') }}" class="d-flex">
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--ms-orange); color: #fff; border-color: var(--ms-orange);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Rechercher des conseils..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-warning">Rechercher</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Conseils en vedette -->
        @if ($astucesConseilsFeatured->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="h4 mb-4 fw-bold"><i class="bi bi-star-fill me-2" style="color: var(--ms-orange);"></i>Conseils populaires</h3>
                    <div class="row g-4">
                        @foreach ($astucesConseilsFeatured as $astuce)
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    @if ($astuce->getFirstMediaUrl('image_principale'))
                                        <img src="{{ $astuce->getFirstMediaUrl('image_principale', 'medium') }}"
                                            class="card-img-top" alt="{{ $astuce->titre }}"
                                            style="height: 220px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top d-flex align-items-center justify-content-center"
                                            style="height: 220px; background: var(--ms-gradient-orange);">
                                            <i class="bi bi-lightbulb text-white" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge" style="background: var(--ms-orange-light); color: var(--ms-orange-dark);">
                                                <i class="bi bi-star-fill me-1"></i>Populaire
                                            </span>
                                            <small class="text-muted">
                                                {{ $astuce->date_publication ? $astuce->date_publication->format('d M Y') : $astuce->created_at->format('d M Y') }}
                                            </small>
                                        </div>
                                        <h5 class="card-title mb-3">{{ $astuce->titre }}</h5>
                                        @if ($astuce->resume)
                                            <p class="card-text text-muted mb-3">{{ Str::limit($astuce->resume, 120) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('rubrique.show', $astuce->slug) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-arrow-right me-1"></i>Découvrir
                                            </a>
                                            <small class="text-muted">
                                                <i class="bi bi-eye me-1"></i>{{ $astuce->nb_vues }} vues
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

        <!-- Liste des astuces et conseils -->
        <div class="row">
            <div class="col-12">
                <h3 class="h4 mb-4 fw-bold">Tous nos conseils</h3>
                @if ($astucesConseils->count() > 0)
                    <div class="row g-4">
                        @foreach ($astucesConseils as $astuce)
                            <div class="col-lg-6 col-md-6">
                                <div class="card h-100">
                                    <div class="row g-0 h-100">
                                        <div class="col-4">
                                            @if ($astuce->getFirstMediaUrl('image_principale'))
                                                <img src="{{ $astuce->getFirstMediaUrl('image_principale', 'thumb') }}"
                                                    class="img-fluid h-100" alt="{{ $astuce->titre }}"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="h-100 d-flex align-items-center justify-content-center"
                                                    style="background: var(--ms-gradient-orange);">
                                                    <i class="bi bi-lightbulb text-white" style="font-size: 2rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-3 h-100 d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge small" style="background: var(--ms-orange-light); color: var(--ms-orange-dark);">Conseil</span>
                                                    <small class="text-muted">
                                                        {{ $astuce->date_publication ? $astuce->date_publication->format('d/m/Y') : $astuce->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                                <h6 class="card-title fw-bold mb-2">{{ Str::limit($astuce->titre, 60) }}
                                                </h6>
                                                @if ($astuce->resume)
                                                    <p class="card-text text-muted small mb-3" style="flex-grow: 1;">
                                                        {{ Str::limit($astuce->resume, 80) }}
                                                    </p>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                                    <a href="{{ route('rubrique.show', $astuce->slug) }}"
                                                        class="btn btn-outline-warning btn-sm">
                                                        Découvrir
                                                    </a>
                                                    <small class="text-muted">
                                                        <i class="bi bi-eye"></i> {{ $astuce->nb_vues }}
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
                            {{ $astucesConseils->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-lightbulb display-1 text-muted mb-3"></i>
                        <h4 class="text-muted">Aucun conseil trouvé</h4>
                        @if (request('search'))
                            <p class="text-muted">Aucun résultat pour "{{ request('search') }}"</p>
                            <a href="{{ route('astuces-conseils.index') }}" class="btn btn-warning">Voir tous les
                                conseils</a>
                        @else
                            <p class="text-muted">Les conseils apparaîtront ici prochainement.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
