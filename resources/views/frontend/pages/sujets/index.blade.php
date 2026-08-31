<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\sujets\index.blade.php -->
@extends('frontend.layouts.front_app')

@section('title', 'Sujets et corrigés d\'examens - MaxiSujets')

@section('content')

    @push('styles')
        <style>
            /* Cartes de sujets */
            .subject-card {
                transition: box-shadow 0.2s ease, border-color 0.2s ease;
                border: 1px solid var(--ms-border-subtle);
                border-radius: var(--ms-radius-lg);
                overflow: hidden;
                box-shadow: var(--ms-shadow-rest);
            }

            .subject-card:hover {
                box-shadow: var(--ms-shadow-hover);
                border-color: var(--ms-border);
            }

            .subject-image {
                border-radius: 10px;
                border: 2px solid #f8f9fa;
                transition: border-color 0.3s ease;
            }

            .subject-card:hover .subject-image {
                border-color: var(--ms-orange);
            }

            .subject-title {
                color: #2d3748;
                font-weight: 600;
                font-size: 1rem;
            }

            .subject-description {
                color: #718096;
                font-size: 0.9rem;
                line-height: 1.4;
            }

            .modern-badge {
                font-size: 0.75rem;
                padding: 0.4rem 0.8rem;
                border-radius: 20px;
                font-weight: 500;
                margin: 0.15rem;
            }

            .badge-matiere { background: var(--ms-blue-light); color: var(--ms-blue-dark); }
            .badge-niveau { background: #f1f5f9; color: #64748b; }
            .badge-annee { background: var(--ms-orange-light); color: var(--ms-orange-dark); }
            .badge-categorie { background: #e7f7ee; color: #147a4a; }
            .badge-code { background: var(--ms-navy); color: white; }

            .publication-date {
                background: #f8fafc;
                color: #64748b;
                padding: 0.3rem 0.8rem;
                border-radius: 15px;
                font-size: 0.8rem;
                font-weight: 500;
                border: 1px solid #e2e8f0;
            }

            /* Barre de recherche */
            .search-section {
                background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 2rem;
            }

            .search-title {
                color: var(--ms-navy);
                font-size: 1.2rem;
                font-weight: 700;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
            }

            .search-title i { color: var(--ms-blue); margin-right: 0.6rem; }

            .search-section .form-select,
            .search-section .form-control {
                border-radius: 12px;
                border: 2px solid #e2e8f0;
                font-weight: 500;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .search-section .form-select:focus,
            .search-section .form-control:focus {
                border-color: var(--ms-blue);
                box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
            }

            .search-submit-btn {
                background: var(--ms-gradient-navy);
                border: none;
                border-radius: 12px;
                color: white;
                font-weight: 600;
                min-width: 50px;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .search-submit-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(13, 110, 253, 0.35);
                color: white;
            }

            .btn-detail-card {
                background: var(--ms-blue);
                border: none;
                border-radius: 12px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-detail-card:hover {
                background: var(--ms-blue-dark);
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(13, 110, 253, 0.3);
                color: white;
            }

            .cost-tag {
                font-size: 0.72rem;
                font-weight: 700;
                color: var(--ms-orange-dark);
                display: inline-flex;
                align-items: center;
                gap: 0.2rem;
                margin-top: 0.25rem;
            }
        </style>
    @endpush

    <div class="container">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
            @include('frontend.components.retour')
        <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
            <ol class="breadcrumb bg-light rounded p-3 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}" class="text-decoration-none">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Sujets</li>
            </ol>
        </nav>
        </div>

        <!-- Bandeau points : contexte visible dès l'arrivée sur le catalogue -->
        @auth
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 p-3 rounded-3"
                style="background: var(--ms-blue-light);">
                <div class="d-flex align-items-center gap-2">
                    <span class="points-pill">
                        <i class="bi bi-star-fill"></i> {{ auth()->user()->points }} point{{ auth()->user()->points > 1 ? 's' : '' }}
                    </span>
                    <span class="text-muted small">1 point est déduit à chaque aperçu ou téléchargement.</span>
                </div>
                <a href="{{ route('user.dashboard') }}" class="small text-decoration-none fw-semibold" style="color: var(--ms-blue-dark);">
                    Comment gagner des points ? <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
        @else
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 p-3 rounded-3"
                style="background: var(--ms-orange-light);">
                <span class="small" style="color: var(--ms-orange-dark);">
                    <i class="bi bi-info-circle me-1"></i>
                    Crée un compte pour recevoir <strong>50 points offerts</strong> et télécharger tes premiers sujets.
                </span>
                <a href="{{ route('user.registerForm') }}" class="btn btn-warning btn-sm fw-semibold">S'inscrire gratuitement</a>
            </div>
        @endauth

        <!-- Recherche / filtres (UI unique) -->
        <div class="search-section">
            <div class="search-title">
                <i class="bi bi-search"></i> Rechercher un sujet
            </div>
            <form class="row g-3" method="GET" action="{{ route('sujet.front.index') }}">
                <div class="col-lg-3 col-md-6">
                    <select class="form-select" id="categorie-select" name="categorie">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->slug }}" {{ request('categorie') == $categorie->slug ? 'selected' : '' }}>
                                {{ $categorie->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <select class="form-select" id="matiere-select" name="matiere">
                        <option value="">Toutes les matières</option>
                        @foreach ($matieres as $matiere)
                            <option value="{{ $matiere->slug }}" {{ request('matiere') == $matiere->slug ? 'selected' : '' }}>
                                {{ $matiere->libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <select class="form-select" id="niveau-select" name="niveau">
                        <option value="">Tous les niveaux</option>
                        @foreach ($data_niveaux as $cycle)
                            <optgroup label="{{ $cycle->libelle }}">
                                @foreach ($cycle->children as $niveau)
                                    <option value="{{ $niveau->slug }}" {{ request('niveau') == $niveau->slug ? 'selected' : '' }}>
                                        {{ $niveau->libelle }}
                                    </option>
                                    @foreach ($niveau->children as $subNiveau)
                                        <option value="{{ $subNiveau->slug }}" {{ request('niveau') == $subNiveau->slug ? 'selected' : '' }}>
                                            &nbsp;&nbsp;{{ $subNiveau->libelle }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <select class="form-select" id="annee-select" name="annee">
                        <option value="">Toutes les années</option>
                        @for ($year = date('Y'); $year >= 2000; $year--)
                            <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-lg-9 col-md-8">
                    <input type="text" class="form-control" name="code" value="{{ request('code') }}" placeholder="Code du sujet">
                </div>
                <div class="col-lg-3 col-md-4">
                    <button type="submit" class="btn search-submit-btn w-100">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                </div>
                @if (request()->anyFilled(['categorie', 'matiere', 'niveau', 'annee', 'code']))
                    <div class="col-12">
                        <a href="{{ route('sujet.front.index') }}" class="small text-decoration-none">
                            <i class="bi bi-x-circle me-1"></i>Réinitialiser les filtres
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Liste des sujets -->
        <div class="row g-4">
            @forelse($sujets as $sujet)
                <div class="col-md-6 col-xl-4">
                    <div class="card subject-card h-100">
                        <div class="card-body p-4 d-flex flex-column">
                            <!-- En-tête avec image et titre -->
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0 me-3">
                                    @php
                                        $media = $sujet->getFirstMedia('non_corrige');
                                        $extension = $media ? strtolower($media->extension) : null;
                                        $isPdf = $extension === 'pdf';
                                        $isDoc = in_array($extension, ['doc', 'docx']);
                                    @endphp
                                    <div class="subject-image d-flex align-items-center justify-content-center bg-light"
                                        style="width:60px; height:60px; overflow:hidden; position:relative;">
                                        @auth
                                            @if ($media && $isPdf)
                                                <iframe src="{{ route('sujet.front.apercu', ['id' => $sujet->id, 'type' => 'non_corrige']) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                                    style="position:absolute; top:0; left:0; width: 260px; height: 260px; border: none; transform: scale(0.23); transform-origin: top left; pointer-events: none;"
                                                    tabindex="-1" title="Aperçu du sujet"></iframe>
                                            @elseif ($isDoc)
                                                <i class="bi bi-filetype-doc text-primary" style="font-size: 1.5rem;"></i>
                                            @else
                                                <i class="bi bi-file-earmark-text text-muted" style="font-size: 1.5rem;"></i>
                                            @endif
                                        @else
                                            @if ($isPdf)
                                                <i class="bi bi-filetype-pdf text-danger" style="font-size: 1.5rem;"></i>
                                            @elseif ($isDoc)
                                                <i class="bi bi-filetype-doc text-primary" style="font-size: 1.5rem;"></i>
                                            @else
                                                <i class="bi bi-file-earmark-text text-muted" style="font-size: 1.5rem;"></i>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="subject-title mb-0 me-2">
                                            {{ Str::limit($sujet->libelle, 40) }}
                                            <span class="text-muted small">{{ $sujet->concours->libelle ?? '' }}</span>
                                        </h6>
                                        <span class="modern-badge badge-code">{{ $sujet->code }}</span>
                                    </div>
                                    <p class="subject-description mb-0 small">{{ Str::limit($sujet->description, 80) }}</p>
                                </div>
                            </div>

                            <!-- Informations -->
                            <div class="mb-3">
                                <div class="row g-2 small">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-book me-1 text-muted"></i>
                                            <span class="modern-badge badge-matiere">{{ $sujet->matiere->libelle ?? 'Non définie' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar me-1 text-muted"></i>
                                            <span class="modern-badge badge-annee">{{ $sujet->annee }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-mortarboard me-1 text-muted"></i>
                                            @if ($sujet->niveaux->count() > 0)
                                                <span class="modern-badge badge-niveau">{{ $sujet->niveaux->first()->libelle }}</span>
                                                @if ($sujet->niveaux->count() > 1)
                                                    <small class="text-muted ms-1">+{{ $sujet->niveaux->count() - 1 }}</small>
                                                @endif
                                            @else
                                                <span class="modern-badge badge-niveau">Tous niveaux</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-tag me-1 text-muted"></i>
                                            <span class="modern-badge badge-categorie">{{ $sujet->categorie->libelle ?? 'Générale' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <small class="text-muted mb-3 d-block">
                                <i class="bi bi-clock me-1"></i>Publié le {{ $sujet->created_at->format('d/m/Y') }}
                            </small>

                            <!-- Actions -->
                            <div class="mt-auto">
                                <a href="{{ route('sujet.front.show', $sujet->libelle) }}"
                                    class="btn btn-detail-card text-white btn-sm w-100 mb-2">
                                    <i class="bi bi-eye me-2"></i>Voir détails et télécharger
                                </a>

                                @auth
                                    @if (auth()->user()->points > 0)
                                        <div class="cost-tag">
                                            <i class="bi bi-star-fill"></i> 1 point par fichier téléchargé
                                        </div>
                                    @else
                                        <div class="cost-tag text-danger">
                                            <i class="bi bi-exclamation-triangle"></i> Points insuffisants pour télécharger
                                        </div>
                                    @endif
                                @else
                                    <div class="cost-tag">
                                        <i class="bi bi-lock"></i> Connexion requise pour télécharger
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info d-flex align-items-center gap-2">
                        <i class="bi bi-emoji-frown fs-4"></i>
                        <div>
                            Aucun sujet ne correspond à ces critères.
                            <a href="{{ route('sujet.front.index') }}">Réinitialiser les filtres</a>.
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $sujets->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#categorie-select, #matiere-select, #niveau-select, #annee-select').select2({
                    width: '100%'
                });
            });
        </script>
    @endpush
@endsection
