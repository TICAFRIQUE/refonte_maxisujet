@extends('frontend.layouts.front_app')

@section('title', 'Sujets et corrigés d\'examens - MaxiSujets')

@section('content')

    @php
        // Filtres actifs : affichés sous forme d'étiquettes supprimables au-dessus des résultats.
        $filtresActifs = [];
        if (request()->filled('q')) {
            $filtresActifs['q'] = '« ' . request('q') . ' »';
        }
        if (request()->filled('categorie')) {
            $filtresActifs['categorie'] = $categories->firstWhere('slug', request('categorie'))->libelle ?? request('categorie');
        }
        if (request()->filled('matiere')) {
            $filtresActifs['matiere'] = $matieres->firstWhere('slug', request('matiere'))->libelle ?? request('matiere');
        }
        if (request()->filled('niveau')) {
            $filtresActifs['niveau'] = $niveaux->firstWhere('slug', request('niveau'))->libelle ?? request('niveau');
        }
        if (request()->filled('annee')) {
            $filtresActifs['annee'] = request('annee');
        }
        if (request()->filled('code')) {
            $filtresActifs['code'] = 'Code ' . request('code');
        }
        $nbFiltresListes = count(array_diff_key($filtresActifs, ['q' => true, 'code' => true]));
    @endphp

    <div class="container">
        <nav aria-label="Fil d'Ariane">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}"><i class="bi bi-house-door"></i> Accueil</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Sujets</li>
            </ol>
        </nav>

        <div class="page-head d-flex flex-wrap justify-content-between align-items-end gap-3">
            <div>
                <h1>Sujets et corrigés</h1>
                <p>
                    {{ number_format($totalSujetsDisponibles, 0, ',', ' ') }} sujets disponibles ·
                    {{ number_format($totalTelechargements, 0, ',', ' ') }} téléchargements
                </p>
            </div>
            @auth
                <a href="{{ route('user.sujet.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-cloud-upload me-2"></i>Publier un sujet
                </a>
            @endauth
        </div>

        <!-- Recherche et filtres : directement sous le titre, rien ne s'intercale avant les résultats.
             Ordre de lecture = ordre d'action : mot-clé, puis filtres, puis le bouton en dernier. -->
        <form class="filter-card" method="GET" action="{{ route('sujet.front.index') }}" role="search" aria-label="Rechercher un sujet">
            <div class="filter-top">
                <div class="filter-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <label for="filter-q" class="visually-hidden">Rechercher un sujet</label>
                    <input type="search" class="form-control" id="filter-q" name="q" value="{{ request('q') }}"
                        placeholder="Matière, niveau, année, code…">
                </div>
                <button type="button" class="btn btn-outline-secondary filter-toggle" data-bs-toggle="collapse"
                    data-bs-target="#filter-fields" aria-expanded="false" aria-controls="filter-fields">
                    <i class="bi bi-sliders"></i>
                    <span class="visually-hidden">Filtres</span>
                    @if ($nbFiltresListes > 0)
                        <span class="filter-count ms-1">{{ $nbFiltresListes }}</span>
                    @endif
                </button>
            </div>

            <div class="collapse filter-fields" id="filter-fields">
                <div class="row g-2">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="categorie-select" class="visually-hidden">Catégorie</label>
                        <select class="form-select" id="categorie-select" name="categorie">
                            <option value="">Toutes les catégories</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->slug }}" {{ request('categorie') == $categorie->slug ? 'selected' : '' }}>
                                    {{ $categorie->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="matiere-select" class="visually-hidden">Matière</label>
                        <select class="form-select" id="matiere-select" name="matiere">
                            <option value="">Toutes les matières</option>
                            @foreach ($matieres as $matiere)
                                <option value="{{ $matiere->slug }}" {{ request('matiere') == $matiere->slug ? 'selected' : '' }}>
                                    {{ $matiere->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="niveau-select" class="visually-hidden">Niveau</label>
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
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="annee-select" class="visually-hidden">Année</label>
                        <select class="form-select" id="annee-select" name="annee">
                            <option value="">Toutes les années</option>
                            @for ($year = date('Y'); $year >= 2000; $year--)
                                <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary filter-submit">
                <i class="bi bi-search me-2"></i>Rechercher
            </button>
        </form>

        <!-- Nombre de résultats + filtres actifs -->
        <div class="result-bar">
            <span>
                <strong>{{ number_format($sujets->total(), 0, ',', ' ') }}</strong>
                {{ $sujets->total() > 1 ? 'sujets trouvés' : 'sujet trouvé' }}
            </span>
            @if ($filtresActifs)
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @foreach ($filtresActifs as $cle => $libelleFiltre)
                        <a href="{{ route('sujet.front.index', \Illuminate\Support\Arr::except(request()->query(), [$cle, 'page'])) }}"
                            class="active-filter" title="Retirer ce filtre">
                            <span class="text-truncate">{{ $libelleFiltre }}</span> <i class="bi bi-x" aria-hidden="true"></i>
                            <span class="visually-hidden">(retirer ce filtre)</span>
                        </a>
                    @endforeach
                    <a href="{{ route('sujet.front.index') }}" class="small fw-semibold text-decoration-none">Tout effacer</a>
                </div>
            @endif
        </div>

        <!-- Liste des sujets -->
        <div class="row g-3" id="sujets-grid" data-next-page-url="{{ $sujets->hasMorePages() ? $sujets->nextPageUrl() : '' }}">
            @include('frontend.pages.sujets.partials._cards', ['sujets' => $sujets])
        </div>

        <div class="text-center mt-4" id="sujets-load-more-wrap"
            style="{{ $sujets->hasMorePages() ? '' : 'display:none;' }}">
            <button type="button" id="sujets-load-more" class="btn btn-load-more">
                <span class="btn-label">Charger plus de sujets</span>
                <span class="btn-spinner spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true" style="display:none;"></span>
            </button>
        </div>

        <noscript>
            <div class="mt-4">
                {{ $sujets->links() }}
            </div>
        </noscript>

        <!-- Rappel du système de points : après les résultats, pour ne pas repousser la recherche
             (le coût figure déjà sur chaque carte, le solde dans la barre de navigation) -->
        @auth
            <div class="notice mt-5">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="points-pill">
                        <i class="bi bi-star-fill"></i> {{ auth()->user()->points }} point{{ auth()->user()->points > 1 ? 's' : '' }}
                    </span>
                    <span>L'aperçu est gratuit. 1 point est déduit à chaque téléchargement.</span>
                </div>
                <a href="{{ route('user.dashboard') }}" class="section-link">
                    Comment gagner des points ? <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
        @else
            <div class="notice notice-orange mt-5">
                <span>
                    <i class="bi bi-gift me-1"></i>
                    Créez un compte et recevez <strong>50 points offerts</strong> pour télécharger vos premiers sujets.
                </span>
                <a href="{{ route('user.registerForm') }}" class="btn btn-warning btn-sm">S'inscrire gratuitement</a>
            </div>
        @endauth
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
