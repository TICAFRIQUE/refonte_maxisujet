@extends('frontend.layouts.front_app')

@section('title', $rubrique->titre . ' - MaxiSujets')
@section('meta_description', $rubrique->resume ?? Str::limit(strip_tags($rubrique->contenu), 160))

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        @include('frontend.components.retour')
    <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
        <ol class="breadcrumb bg-light rounded p-3">
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}" class="text-decoration-none">
                    <i class="bi bi-house-door"></i> Accueil
                </a>
            </li>
            <li class="breadcrumb-item">
                @if($rubrique->type_rubrique === 'actualite')
                    <a href="{{ route('actualites.index') }}" class="text-decoration-none">Actualités</a>
                @else
                    <a href="{{ route('astuces-conseils.index') }}" class="text-decoration-none">Astuces & Conseils</a>
                @endif
            </li>
            <li class="breadcrumb-item active">{{ Str::limit($rubrique->titre, 30) }}</li>
        </ol>
    </nav>
    </div>

    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <article class="card" style="overflow: hidden;">
                <!-- Image principale -->
                @if($rubrique->getFirstMediaUrl('image_principale'))
                    <img src="{{ $rubrique->getFirstMediaUrl('image_principale') }}" 
                         class="card-img-top" alt="{{ $rubrique->titre }}" style="height: 400px; object-fit: cover;">
                @endif

                <div class="card-body p-4 p-md-5">
                    <!-- En-tête de l'article -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            @if($rubrique->type_rubrique === 'actualite')
                                <span class="badge" style="background: var(--ms-blue-light); color: var(--ms-blue-dark);">
                                    <i class="bi bi-newspaper me-1"></i>Actualité
                                </span>
                            @else
                                <span class="badge" style="background: var(--ms-orange-light); color: var(--ms-orange-dark);">
                                    <i class="bi bi-lightbulb me-1"></i>Astuce & Conseil
                                </span>
                            @endif
                            @if($rubrique->est_featured)
                                <span class="badge" style="background: var(--ms-danger-bg); color: var(--ms-danger);">
                                    <i class="bi bi-star-fill me-1"></i>En vedette
                                </span>
                            @endif
                        </div>
                        <div class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $rubrique->date_publication ? $rubrique->date_publication->format('d M Y') : $rubrique->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <!-- Titre -->
                    <h1 class="display-5 fw-bold mb-4">{{ $rubrique->titre }}</h1>

                    <!-- Métadonnées -->
                    <div class="row text-muted mb-4">
                        @if($rubrique->auteur)
                            <div class="col-md-6">
                                <i class="bi bi-person me-1"></i>
                                Par <strong>{{ $rubrique->auteur->name }}</strong>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <i class="bi bi-eye me-1"></i>
                            {{ $rubrique->nb_vues }} vue{{ $rubrique->nb_vues > 1 ? 's' : '' }}
                        </div>
                    </div>

                    <!-- Résumé -->
                    @if($rubrique->resume)
                        <div class="alert alert-light border-start border-4 border-{{ $rubrique->type_rubrique === 'actualite' ? 'primary' : 'warning' }} mb-4">
                            <strong>En résumé :</strong> {{ $rubrique->resume }}
                        </div>
                    @endif

                    <!-- Contenu -->
                    <div class="content-body">
                        {!! nl2br(e($rubrique->contenu)) !!}
                    </div>

                    <!-- Tags -->
                    @if($rubrique->tags && count($rubrique->tags) > 0)
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="mb-3">Tags :</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($rubrique->tags as $tag)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-tag me-1"></i>{{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Partage -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="mb-3">Partager cet article :</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                               target="_blank" rel="noopener" class="btn btn-sm text-white" style="background: #1877f2;">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($rubrique->titre) }}"
                               target="_blank" rel="noopener" class="btn btn-sm text-white" style="background: #1da1f2;">
                                <i class="bi bi-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($rubrique->titre . ' - ' . request()->fullUrl()) }}"
                               target="_blank" rel="noopener" class="btn btn-sm text-white" style="background: #25d366;">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Articles similaires -->
            @if($rubriquesSimilaires->count() > 0)
                <div class="card mb-4">
                    <div class="card-header text-white" style="background: {{ $rubrique->type_rubrique === 'actualite' ? 'var(--ms-blue)' : 'var(--ms-orange)' }};">
                        <h6 class="mb-0 fw-bold">
                            @if($rubrique->type_rubrique === 'actualite')
                                <i class="bi bi-newspaper me-2"></i>Autres actualités
                            @else
                                <i class="bi bi-lightbulb me-2"></i>Autres conseils
                            @endif
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($rubriquesSimilaires as $similaire)
                            <div class="d-flex p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                @if($similaire->getFirstMediaUrl('image_principale'))
                                    <img src="{{ $similaire->getFirstMediaUrl('image_principale', 'thumb') }}" 
                                         class="rounded me-3" alt="{{ $similaire->titre }}" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="rounded me-3 d-flex align-items-center justify-content-center bg-light" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-{{ $similaire->type_rubrique === 'actualite' ? 'newspaper' : 'lightbulb' }} text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('rubrique.show', $similaire->slug) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ Str::limit($similaire->titre, 50) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $similaire->date_publication ? $similaire->date_publication->format('d M Y') : $similaire->created_at->format('d M Y') }}
                                        <span class="ms-2">
                                            <i class="bi bi-eye me-1"></i>{{ $similaire->nb_vues }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Navigation -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-compass me-2"></i>Navigation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('actualites.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-newspaper me-2"></i>Toutes les actualités
                        </a>
                        <a href="{{ route('astuces-conseils.index') }}" class="btn btn-outline-warning">
                            <i class="bi bi-lightbulb me-2"></i>Tous les conseils
                        </a>
                        <a href="{{ route('sujet.front.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-file-text me-2"></i>Sujets
                        </a>
                        <a href="{{ route('accueil') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house-door me-2"></i>Accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .content-body {
        line-height: 1.8;
        font-size: 1.1rem;
    }
    
    .content-body p {
        margin-bottom: 1.5rem;
    }
    
    .content-body h1, .content-body h2, .content-body h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .content-body ul, .content-body ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .content-body li {
        margin-bottom: 0.5rem;
    }
</style>
@endsection