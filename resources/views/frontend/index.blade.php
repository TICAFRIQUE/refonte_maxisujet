@extends('frontend.layouts.front_app')

@section('title', 'MaxiSujets - Plateforme N°1 de Documents Éducatifs en Côte d\'Ivoire')
@section('meta_description',
    'Téléchargez gratuitement des milliers de documents éducatifs : cours, exercices corrigés,
    examens blancs, sujets de concours. Ressources pour primaire, secondaire et supérieur.')
@section('meta_keywords',
    'documents scolaires côte d\'ivoire, cours gratuits CI, exercices corrigés, examens blancs,
    sujets concours, BEPC, BAC, université côte d\'ivoire, ressources éducatives')
@section('og_title', 'MaxiSujets - Documents Éducatifs Gratuits Côte d\'Ivoire')
@section('og_description',
    'La plus grande bibliothèque de documents éducatifs en Côte d\'Ivoire. Cours, exercices,
    examens pour tous les niveaux.')

@section('content')
    @php
        $rubriques = app('App\Http\Controllers\frontend\RubriqueFrontController');
        $actualitesAccueil = $rubriques->getActualitesRecentes(3);
        $astucesAccueil = $rubriques->getAstucesConseils(3);
    @endphp

    <!-- Hero : la promesse, puis tout de suite la recherche (l'action n°1 du site) -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-7">
                    <span class="eyebrow">Sujets d'examens · corrigés · annales</span>
                    <h1>Tous les sujets d'examens, au même endroit</h1>
                    <p class="lead">
                        Trouvez le sujet qu'il vous faut pour réviser : déposé par la communauté, vérifié par notre équipe.
                    </p>

                    <form class="hero-search" method="GET" action="{{ route('sujet.front.index') }}" role="search">
                        <div class="hero-search-field">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            <input type="search" name="q" placeholder="Ex. : mathématiques terminale 2021" aria-label="Rechercher un sujet">
                        </div>
                        <button type="submit" class="btn btn-warning px-4">Rechercher</button>
                    </form>

                    @if ($data_categories->isNotEmpty())
                        <div class="hero-shortcuts">
                            <span>Parcourir :</span>
                            @foreach ($data_categories->take(5) as $categorie)
                                <a href="{{ route('sujet.front.index', ['categorie' => $categorie->slug]) }}" class="tag-link">{{ $categorie->libelle }}</a>
                            @endforeach
                        </div>
                    @endif

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <strong>{{ number_format($footer_stats['sujets'] ?? 0, 0, ',', ' ') }}</strong>
                            <span>sujets disponibles</span>
                        </div>
                        <div class="hero-stat">
                            <strong>{{ number_format($footer_stats['membres'] ?? 0, 0, ',', ' ') }}</strong>
                            <span>membres inscrits</span>
                        </div>
                        <div class="hero-stat">
                            <strong>50 points</strong>
                            <span>offerts à l'inscription</span>
                        </div>
                    </div>
                </div>

                <!-- Illustration décorative : masquée sur mobile pour laisser la recherche au-dessus de la ligne de flottaison -->
                <div class="col-lg-5 d-none d-lg-block" aria-hidden="true">
                    <div class="hero-illustration">
                        <div class="position-absolute w-100 h-100 rounded-circle" style="background: linear-gradient(135deg, rgba(255, 107, 53, 0.12) 0%, rgba(26, 86, 219, 0.12) 100%);"></div>

                        <svg class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 230px; height: 250px;" viewBox="0 0 230 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Fiche du fond -->
                            <g transform="rotate(-14 60 95)">
                                <rect x="15" y="60" width="95" height="65" rx="6" fill="#ffffff" stroke="#e3e2e6" stroke-width="1.5"/>
                                <rect x="15" y="60" width="95" height="9" rx="4" fill="var(--ms-orange)"/>
                                <line x1="27" y1="88" x2="93" y2="88" stroke="#dcdbe0" stroke-width="3" stroke-linecap="round"/>
                                <line x1="27" y1="99" x2="93" y2="99" stroke="#dcdbe0" stroke-width="3" stroke-linecap="round"/>
                                <line x1="27" y1="110" x2="70" y2="110" stroke="#dcdbe0" stroke-width="3" stroke-linecap="round"/>
                            </g>

                            <!-- Fiche du milieu -->
                            <g transform="rotate(-5 75 100)">
                                <rect x="25" y="68" width="95" height="65" rx="6" fill="#ffffff" stroke="#e3e2e6" stroke-width="1.5"/>
                                <rect x="25" y="68" width="95" height="9" rx="4" fill="var(--ms-blue)"/>
                                <line x1="37" y1="96" x2="103" y2="96" stroke="#dcdbe0" stroke-width="3" stroke-linecap="round"/>
                                <line x1="37" y1="107" x2="103" y2="107" stroke="#dcdbe0" stroke-width="3" stroke-linecap="round"/>
                                <line x1="37" y1="118" x2="80" y2="118" stroke="#dcdbe0" stroke-width="3" stroke-linecap="round"/>
                            </g>

                            <!-- Cahier (premier plan) -->
                            <g transform="rotate(4 145 150)">
                                <rect x="65" y="55" width="150" height="190" rx="10" fill="#ffffff" stroke="#e3e2e6" stroke-width="1.5"/>
                                <path d="M65 65 a10 10 0 0 1 10 -10 h10 v190 h-10 a10 10 0 0 1 -10 -10 Z" fill="var(--ms-navy)"/>
                                <line x1="100" y1="75" x2="100" y2="225" stroke="#f1948a" stroke-width="1.5"/>
                                <line x1="108" y1="80" x2="160" y2="80" stroke="var(--ms-ink)" stroke-width="4" stroke-linecap="round" opacity="0.65"/>
                                <line x1="108" y1="98" x2="200" y2="98" stroke="#cfd8ea" stroke-width="3" stroke-linecap="round"/>
                                <line x1="108" y1="113" x2="200" y2="113" stroke="#cfd8ea" stroke-width="3" stroke-linecap="round"/>
                                <line x1="108" y1="128" x2="185" y2="128" stroke="#cfd8ea" stroke-width="3" stroke-linecap="round"/>
                                <line x1="108" y1="143" x2="200" y2="143" stroke="#cfd8ea" stroke-width="3" stroke-linecap="round"/>
                                <line x1="108" y1="158" x2="175" y2="158" stroke="#cfd8ea" stroke-width="3" stroke-linecap="round"/>
                                <line x1="108" y1="173" x2="200" y2="173" stroke="#cfd8ea" stroke-width="3" stroke-linecap="round"/>
                            </g>

                            <!-- Crayon -->
                            <g transform="rotate(-38 150 190)">
                                <rect x="90" y="183" width="120" height="13" rx="4" fill="var(--ms-orange)"/>
                                <rect x="90" y="183" width="16" height="13" fill="#f4c78a"/>
                                <path d="M74 189.5 L90 183 L90 196 Z" fill="#3a3a3a"/>
                                <rect x="205" y="183" width="16" height="13" rx="4" fill="var(--ms-blue)"/>
                            </g>

                            <!-- Pastille "corrigé" -->
                            <circle cx="200" cy="45" r="21" fill="var(--ms-orange)" stroke="#ffffff" stroke-width="3"/>
                            <path d="M190 45 L197 52 L211 36" stroke="#ffffff" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <!-- Repères de niveaux, sobres (3 max, pas de nuage d'étiquettes) -->
                        <div class="position-absolute" style="top: 8%; right: 10%;">
                            <div class="bg-white text-dark px-3 py-1 rounded-pill shadow-sm fw-semibold" style="font-size: 0.75rem;">BAC</div>
                        </div>
                        <div class="position-absolute" style="bottom: 30%; left: 0%;">
                            <div class="bg-white text-dark px-3 py-1 rounded-pill shadow-sm fw-semibold" style="font-size: 0.75rem;">BEPC</div>
                        </div>
                        <div class="position-absolute" style="bottom: 5%; right: 20%;">
                            <div class="bg-white text-dark px-3 py-1 rounded-pill shadow-sm fw-semibold" style="font-size: 0.75rem;">Concours</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comment ça marche : le système de points expliqué en trois étapes -->
    <section class="section" aria-labelledby="home-etapes">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2 id="home-etapes">Comment ça marche ?</h2>
                    <p>Les sujets se téléchargent avec des points. En gagner est simple.</p>
                </div>
            </div>
            <div class="row g-3 steps">
                <div class="col-md-4">
                    <div class="step-card">
                        <h3>Créez votre compte</h3>
                        <p>L'inscription est gratuite et prend une minute.</p>
                        <span class="points-pill"><i class="bi bi-star-fill"></i>+50 points offerts</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <h3>Téléchargez vos sujets</h3>
                        <p>L'aperçu est gratuit. Chaque téléchargement, sujet ou corrigé, coûte un point.</p>
                        <span class="points-pill"><i class="bi bi-star-fill"></i>1 point par fichier</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-card">
                        <h3>Partagez pour en gagner</h3>
                        <p>Publiez vos propres sujets : chaque publication approuvée recharge votre solde.</p>
                        <span class="points-pill"><i class="bi bi-star-fill"></i>+100 points par sujet</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Parcourir par niveau : porte d'entrée pour ceux qui ne cherchent pas par mot-clé -->
    <section class="section section-alt" aria-labelledby="home-niveaux">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2 id="home-niveaux">Parcourir par niveau</h2>
                    <p>Choisissez votre classe pour voir les sujets qui vous concernent.</p>
                </div>
                <a href="{{ route('sujet.front.index') }}" class="section-link">Tous les sujets <i class="bi bi-arrow-right-short"></i></a>
            </div>
            @include('frontend.components.cycle_niveaux_improved')
        </div>
    </section>

    <!-- Derniers sujets -->
    <section class="section" aria-labelledby="home-recents">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2 id="home-recents">Derniers sujets ajoutés</h2>
                    <p>Les ajouts les plus récents de la bibliothèque.</p>
                </div>
                <a href="{{ route('sujet.front.index') }}" class="section-link">Voir tous les sujets <i class="bi bi-arrow-right-short"></i></a>
            </div>
            <div class="row g-3">
                @include('frontend.pages.sujets.partials._cards', ['sujets' => $sujetsRecents->take(6)])
            </div>
        </div>
    </section>
    {{-- La modale "connexion requise" est partagée, définie une seule fois dans le layout (front_app.blade.php). --}}

    <!-- Matières -->
    <section class="section section-alt" aria-labelledby="home-matieres">
        <div class="container">
            <div class="section-head">
                <div>
                    <h2 id="home-matieres">Parcourir par matière</h2>
                    <p>{{ $data_matieres->count() }} matières, du primaire au supérieur.</p>
                </div>
            </div>
            @include('frontend.components.matieres_improved')
        </div>
    </section>

    <!-- Actualités et conseils : deux colonnes compactes au lieu de deux sections de grandes cartes -->
    @if ($actualitesAccueil->isNotEmpty() || $astucesAccueil->isNotEmpty())
        <section class="section" aria-label="Actualités et conseils">
            <div class="container">
                <div class="row g-4 g-lg-5">
                    @foreach ([
                        ['titre' => 'Actualités', 'items' => $actualitesAccueil, 'route' => 'actualites.index', 'lien' => 'Toutes les actualités', 'icon' => 'bi-newspaper', 'thumb' => ''],
                        ['titre' => 'Astuces & conseils', 'items' => $astucesAccueil, 'route' => 'astuces-conseils.index', 'lien' => 'Tous les conseils', 'icon' => 'bi-lightbulb', 'thumb' => 'post-thumb-orange'],
                    ] as $bloc)
                        @continue($bloc['items']->isEmpty())
                        <div class="col-lg-6">
                            <div class="section-head">
                                <h2 class="h3 mb-0">{{ $bloc['titre'] }}</h2>
                                <a href="{{ route($bloc['route']) }}" class="section-link">{{ $bloc['lien'] }} <i class="bi bi-arrow-right-short"></i></a>
                            </div>
                            <div class="post-list">
                                @foreach ($bloc['items'] as $article)
                                    <article class="post-row">
                                        @if ($article->getFirstMediaUrl('image_principale'))
                                            <img src="{{ $article->getFirstMediaUrl('image_principale', 'thumb') }}" alt="" class="post-thumb" loading="lazy">
                                        @else
                                            <span class="post-thumb {{ $bloc['thumb'] }}" aria-hidden="true"><i class="bi {{ $bloc['icon'] }}"></i></span>
                                        @endif
                                        <div class="min-w-0">
                                            <h3><a href="{{ route('rubrique.show', $article->slug) }}" class="stretched-link">{{ $article->titre }}</a></h3>
                                            <div class="post-meta">
                                                {{ ($article->date_publication ?? $article->created_at)->translatedFormat('d M Y') }}
                                                · {{ $article->nb_vues }} vues
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Appel à l'action final : une action principale, une secondaire -->
    <section class="section pt-0">
        <div class="container">
            <div class="cta-band">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        @guest
                            <h2>Rejoignez la communauté MaxiSujets</h2>
                            <p>Créez votre compte gratuit, recevez 50 points et téléchargez vos premiers sujets dès aujourd'hui.</p>
                        @else
                            <h2>Vous avez des sujets à partager ?</h2>
                            <p>Chaque sujet approuvé vous rapporte 100 points et aide d'autres élèves à réussir.</p>
                        @endguest
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-lg-end">
                            @guest
                                <a href="{{ route('user.registerForm') }}" class="btn btn-warning btn-lg">Créer un compte gratuit</a>
                                <a href="{{ route('user.sujet.create') }}" class="btn btn-on-dark btn-lg">Publier un sujet</a>
                            @else
                                <a href="{{ route('user.sujet.create') }}" class="btn btn-warning btn-lg">Publier un sujet</a>
                                <a href="{{ route('sujet.front.index') }}" class="btn btn-on-dark btn-lg">Parcourir les sujets</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
