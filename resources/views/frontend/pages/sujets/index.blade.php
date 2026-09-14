<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\sujets\index.blade.php -->
@extends('frontend.layouts.front_app')

@section('title', 'Sujets et corrigés d\'examens - MaxiSujets')

@section('content')

    @push('styles')
        @include('frontend.pages.sujets.partials._card-styles')
        <style>
            .btn-load-more {
                background: var(--ms-blue-light);
                color: var(--ms-blue-dark);
                border: 1px solid var(--ms-blue-light);
                border-radius: 12px;
                font-weight: 500;
                font-size: 0.85rem;
                padding: 0.55rem 1.5rem;
                transition: background 0.2s ease;
            }

            .btn-load-more:hover { background: var(--ms-blue-light); filter: brightness(0.96); }
            .btn-load-more:disabled { opacity: 0.6; }

            .stats-band {
                border-top: 1px solid var(--ms-border-subtle);
                padding-top: 2rem;
            }

            .stats-card {
                text-align: center;
                background: var(--ms-orange-light);
                border-radius: var(--ms-radius-lg);
                padding: 1.25rem 1rem;
            }

            .stats-card.stats-card-blue { background: var(--ms-blue-light); }

            .stats-number {
                font-size: 1.6rem;
                font-weight: 700;
                color: var(--ms-navy);
                line-height: 1.2;
            }

            .stats-label {
                font-size: 0.78rem;
                font-weight: 400;
                color: #64748b;
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

            /* Select2 génère son propre balisage à côté du <select> natif caché :
               les règles ci-dessus ne s'appliquent pas à son rendu visible, d'où ces
               surcharges ciblées sur les classes .select2-*. */
            .search-section .select2-container {
                width: 100% !important;
            }

            .search-section .select2-container--default .select2-selection--single {
                height: 46px;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                display: flex;
                align-items: center;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .search-section .select2-container--default.select2-container--open .select2-selection--single,
            .search-section .select2-container--default .select2-selection--single:hover {
                border-color: var(--ms-blue);
            }

            .search-section .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: var(--ms-blue);
                box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
            }

            .search-section .select2-container--default .select2-selection--single .select2-selection__rendered {
                padding-left: 1rem;
                font-weight: 500;
                color: #2d3748;
            }

            .search-section .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 44px;
                right: 0.6rem;
            }

            .search-section .select2-dropdown {
                border: 2px solid var(--ms-blue);
                border-radius: 12px;
                overflow: hidden;
                box-shadow: var(--ms-shadow-hover);
            }

            .search-section .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: var(--ms-blue);
            }

            .search-section .select2-search--dropdown .select2-search__field {
                border-radius: 8px;
                border: 1px solid #e2e8f0;
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
        <div class="row g-3" id="sujets-grid" data-next-page-url="{{ $sujets->hasMorePages() ? $sujets->nextPageUrl() : '' }}">
            @include('frontend.pages.sujets.partials._cards', ['sujets' => $sujets])
        </div>

        <div class="text-center mt-4 mb-5" id="sujets-load-more-wrap"
            style="{{ $sujets->hasMorePages() ? '' : 'display:none;' }}">
            <button type="button" id="sujets-load-more" class="btn btn-load-more">
                <span class="btn-label">Charger plus de sujets</span>
                <span class="btn-spinner spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true" style="display:none;"></span>
            </button>
        </div>

        <noscript>
            <div class="mt-4 mb-5">
                {{ $sujets->links() }}
            </div>
        </noscript>

        <!-- Statistiques -->
        <div class="row g-3 stats-band mb-5">
            <div class="col-6 col-md-3 offset-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ number_format($totalSujetsDisponibles, 0, ',', ' ') }}</div>
                    <div class="stats-label"><i class="bi bi-journal-text me-1"></i>Sujets disponibles</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stats-card stats-card-blue">
                    <div class="stats-number">{{ number_format($totalTelechargements, 0, ',', ' ') }}</div>
                    <div class="stats-label"><i class="bi bi-download me-1"></i>Téléchargements</div>
                </div>
            </div>
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
        <script>
            (function () {
                var grid = document.getElementById('sujets-grid');
                var wrap = document.getElementById('sujets-load-more-wrap');
                var button = document.getElementById('sujets-load-more');
                var label = button.querySelector('.btn-label');
                var spinner = button.querySelector('.btn-spinner');

                var nextUrl = grid.dataset.nextPageUrl || null;
                var busy = false;

                button.addEventListener('click', function () {
                    if (busy || !nextUrl) return;
                    busy = true;
                    button.disabled = true;
                    label.textContent = 'Chargement...';
                    spinner.style.display = 'inline-block';

                    fetch(nextUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(function (response) {
                            nextUrl = response.headers.get('X-Next-Page') || null;
                            return response.text();
                        })
                        .then(function (html) {
                            grid.insertAdjacentHTML('beforeend', html);
                            busy = false;
                            button.disabled = false;
                            label.textContent = 'Charger plus de sujets';
                            spinner.style.display = 'none';
                            if (!nextUrl) {
                                wrap.style.display = 'none';
                            }
                        })
                        .catch(function () {
                            busy = false;
                            button.disabled = false;
                            label.textContent = 'Charger plus de sujets';
                            spinner.style.display = 'none';
                        });
                });
            })();
        </script>
    @endpush
@endsection
