<!-- filepath: resources/views/frontend/index.blade.php -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $seoNom = $parametre?->nom_projet ?? 'MaxiSujets';
        $seoDescription = $parametre?->description_projet
            ?? 'Téléchargez des milliers de documents éducatifs : cours, exercices, examens, concours. Ressources gratuites pour élèves, étudiants et enseignants.';
        $seoLogo = $parametre?->getFirstMediaUrl('logo_header') ?: asset('frontend/images/logo-social.png');
    @endphp

    <!-- SEO Meta Tags -->
    <title>@yield('title', $seoNom . ' - Plateforme Éducative de Documents Scolaires et Universitaires')</title>
    <meta name="description" content="@yield('meta_description', $seoDescription)">
    <meta name="keywords" content="@yield('meta_keywords', 'documents scolaires, cours gratuits, exercices, examens, concours, ressources éducatives, téléchargement, étudiant, élève, enseignant, université, lycée, collège')">
    <meta name="author" content="{{ $seoNom }}">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <meta name="language" content="fr">
    <meta name="revisit-after" content="7 days">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', $seoNom . ' - Plateforme Éducative de Documents Scolaires')">
    <meta property="og:description" content="@yield('og_description', $seoDescription)">
    <meta property="og:image" content="@yield('og_image', $seoLogo)">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ $seoNom }}">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('twitter_url', url()->current())">
    <meta name="twitter:title" content="@yield('twitter_title', $seoNom . ' - Documents Éducatifs Gratuits')">
    <meta name="twitter:description" content="@yield('twitter_description', $seoDescription)">
    <meta name="twitter:image" content="@yield('twitter_image', $seoLogo)">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('frontend/images/site.webmanifest') }}">

    <!-- Additional SEO -->
    <meta name="theme-color" content="#1a56db">
    <meta name="msapplication-TileColor" content="#1a56db">
    <meta name="application-name" content="{{ $seoNom }}">
    <meta name="msapplication-tooltip" content="Plateforme de documents éducatifs">

    <!-- Schema.org structured data -->
    @php
        $ldJson = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => $seoNom,
            'description' => $seoDescription,
            'url' => url('/'),
            'logo' => $seoLogo,
            'email' => $parametre?->email1 ?? 'info@maxisujets.net',
            'telephone' => $parametre?->contact1,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $parametre?->localisation ?? 'Abidjan',
                'addressCountry' => 'CI',
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($ldJson, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    <!-- Police unique (Inter, plusieurs graisses) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!--CDN  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Select2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}?v={{ @filemtime(public_path('frontend/css/style.css')) ?: '1' }}">

    @stack('styles')

</head>

<body>

    <a class="visually-hidden-focusable" href="#main-content">Aller au contenu principal</a>

    <!-- En-tête collant : bandeau d'annonces + navigation dans un seul bloc -->
    <header class="site-header">
        @if (isset($info_flashes) && $info_flashes->isNotEmpty())
            <div id="infoFlashBanner" class="info-flash-banner" role="region" aria-label="Annonces">
                @foreach ($info_flashes as $flash)
                    <div class="info-flash-item info-flash-{{ $flash->type }} {{ $loop->first ? 'is-active' : '' }}">
                        <div class="info-flash-content">
                            <span class="info-flash-label">
                                <i class="bi bi-megaphone-fill"></i> <span class="info-flash-label-text">Info</span>
                            </span>
                            <span class="info-flash-message"><span class="info-flash-message-inner">{{ $flash->message }}</span></span>
                            @if ($flash->lien)
                                <a href="{{ $flash->lien }}" class="info-flash-link">
                                    <span class="info-flash-link-text">{{ $flash->lien_texte ?: 'En savoir plus' }}</span> <i class="bi bi-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                        <button type="button" class="info-flash-close" aria-label="Fermer les annonces">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <nav class="navbar navbar-expand-lg" aria-label="Navigation principale">
            <div class="container">
                <a class="navbar-brand" href="{{ route('accueil') }}">
                    <img src="{{ $parametre?->getFirstMediaUrl('logo_header') ?: asset('frontend/img/logo.png') }}"
                        alt="{{ $parametre?->nom_projet ?? 'MaxiSujets' }} — accueil">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Ouvrir le menu">
                    <span class="hamburger-icon" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('accueil') ? 'active' : '' }}" href="{{ route('accueil') }}"
                                @if (request()->routeIs('accueil')) aria-current="page" @endif>Accueil</a>
                        </li>
                        <!-- Sujets : une seule entrée pour le catalogue et ses catégories -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('sujet.front.*') ? 'active' : '' }}"
                                href="{{ route('sujet.front.index') }}" id="sujetsDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Sujets
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="sujetsDropdown">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('sujet.front.index') && !request('categorie') ? 'active' : '' }}"
                                        href="{{ route('sujet.front.index') }}">
                                        <i class="bi bi-collection me-2"></i>Tous les sujets
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Par catégorie</h6></li>
                                @foreach ($data_categories as $item)
                                    <li>
                                        <a class="dropdown-item {{ request('categorie') == $item->slug ? 'active' : '' }}"
                                            href="{{ route('sujet.front.index', ['categorie' => $item->slug]) }}">{{ $item->libelle }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('actualites.*') ? 'active' : '' }}" href="{{ route('actualites.index') }}"
                                @if (request()->routeIs('actualites.*')) aria-current="page" @endif>Actualités</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('astuces-conseils.*') ? 'active' : '' }}" href="{{ route('astuces-conseils.index') }}"
                                @if (request()->routeIs('astuces-conseils.*')) aria-current="page" @endif>Conseils</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}"
                                @if (request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
                        </li>
                    </ul>

                    {{-- Pas de recherche dans la barre sur le catalogue : la page a déjà son propre champ, deux champs identiques prêtaient à confusion --}}
                    @unless (request()->routeIs('sujet.front.index'))
                        <form class="nav-search" role="search" method="GET" action="{{ route('sujet.front.index') }}">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            <input type="search" name="q" placeholder="Matière, niveau, code…" aria-label="Rechercher un sujet">
                        </form>
                    @endunless

                    <div class="nav-actions">
                        @guest
                            <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary">Connexion</a>
                            <a href="{{ route('user.registerForm') }}" class="btn btn-warning">S'inscrire</a>
                        @else
                            @php $navPoints = (int) (Auth::user()->points ?? 0); @endphp
                            <a href="{{ route('user.dashboard') }}" class="points-pill" title="Mon solde de points">
                                <i class="bi bi-star-fill"></i> {{ $navPoints }}<span class="d-lg-none d-xl-inline">point{{ $navPoints > 1 ? 's' : '' }}</span>
                            </a>
                            <div class="dropdown">
                                <button class="btn nav-user-toggle dropdown-toggle" type="button" id="userMenu"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="nav-avatar" aria-hidden="true">{{ Str::substr(Auth::user()->username ?? Auth::user()->email, 0, 1) }}</span>
                                    <span class="nav-user-name">{{ Auth::user()->username ?? Auth::user()->email }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                    @if (Auth::user()->hasAnyRole(['administrateur', 'developpeur', 'superadmin']))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                                <i class="bi bi-shield-lock me-2"></i>Espace administration
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.sujet.index') }}">
                                            <i class="bi bi-files me-2"></i>Mes sujets
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.sujet.create') }}">
                                            <i class="bi bi-cloud-upload me-2"></i>Publier un sujet
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.dashboard') }}#section-profil">
                                            <i class="bi bi-person me-2"></i>Mon profil
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('user.logout') }}">
                                            @csrf
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Afficher les messages d'alerte -->
    @include('sweetalert::alert')
    <!--Afficher le contenu spécifique de chaque page -->
    <main id="main-content">
        @yield('content')
    </main>

    @php
        $footerNom = $parametre?->nom_projet ?? 'MaxiSujets';
        $footerDescription = $parametre?->description_projet
            ?? "Ce site regroupe de nombreux supports de sujets et de cours portant sur divers domaines de votre parcours scolaire, universitaire et votre entrée dans la vie professionnelle.";
        $footerEmail = $parametre?->email1 ?? 'info@maxisujets.net';
        $footerTel = $parametre?->contact1 ?? '+225 25 22 00 20 77';
        $footerAdresse = $parametre?->localisation ?? "Abidjan, Côte d'Ivoire";
        $footerHoraires = $parametre?->horaires ?? '24h/7j disponible';
        $footerLogo = $parametre?->getFirstMediaUrl('logo_footer') ?: asset('frontend/img/logo.png');
        $footerWhatsapp = preg_replace('/\D+/', '', $footerTel);
        $footerSocials = array_filter([
            'facebook' => ['url' => $parametre?->lien_facebook, 'icon' => 'bi-facebook'],
            'instagram' => ['url' => $parametre?->lien_instagram, 'icon' => 'bi-instagram'],
            'linkedin' => ['url' => $parametre?->lien_linkedin, 'icon' => 'bi-linkedin'],
            'twitter' => ['url' => $parametre?->lien_twitter, 'icon' => 'bi-twitter-x'],
            'tiktok' => ['url' => $parametre?->lien_tiktok, 'icon' => 'bi-tiktok'],
        ], fn($social) => !empty($social['url']));
    @endphp

    <!-- Pied de page -->
    <footer class="modern-footer">
        <div class="footer-main">
            <div class="container">
                <div class="row g-4">
                    <!-- À propos -->
                    <div class="col-lg-4">
                        <div class="footer-logo mb-3">
                            <img src="{{ $footerLogo }}" alt="{{ $footerNom }}" class="footer-logo-img" loading="lazy">
                        </div>
                        <p class="footer-description">{{ Str::limit($footerDescription, 220) }}</p>
                        <div class="footer-stats mb-3">
                            <div class="stat-item">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>{{ number_format($footer_stats['sujets'] ?? 0, 0, ',', ' ') }} sujets</span>
                            </div>
                            <div class="stat-item">
                                <i class="bi bi-people"></i>
                                <span>{{ number_format($footer_stats['membres'] ?? 0, 0, ',', ' ') }} membres</span>
                            </div>
                        </div>
                        <div class="social-links">
                            <a href="https://wa.me/{{ $footerWhatsapp }}" class="social-link whatsapp" title="WhatsApp" aria-label="WhatsApp" target="_blank" rel="noopener">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="mailto:{{ $footerEmail }}" class="social-link email" title="Email" aria-label="Email">
                                <i class="bi bi-envelope"></i>
                            </a>
                            @foreach ($footerSocials as $key => $social)
                                <a href="{{ $social['url'] }}" class="social-link {{ $key }}" title="{{ ucfirst($key) }}" aria-label="{{ ucfirst($key) }}" target="_blank" rel="noopener">
                                    <i class="bi {{ $social['icon'] }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <h2 class="footer-title">Navigation</h2>
                        <ul class="footer-links">
                            <li><a href="{{ route('accueil') }}">Accueil</a></li>
                            <li><a href="{{ route('sujet.front.index') }}">Tous les sujets</a></li>
                            <li><a href="{{ route('actualites.index') }}">Actualités</a></li>
                            <li><a href="{{ route('astuces-conseils.index') }}">Conseils</a></li>
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Catégories -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <h2 class="footer-title">Catégories</h2>
                        <ul class="footer-links">
                            @foreach ($data_categories->take(5) as $category)
                                <li><a href="{{ route('sujet.front.index', ['categorie' => $category->slug]) }}">{{ $category->libelle }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div class="col-md-4 col-lg-4">
                        <h2 class="footer-title">Contact</h2>
                        <div class="footer-contact">
                            <div class="contact-item">
                                <i class="bi bi-envelope"></i>
                                <div>
                                    <strong>Email</strong>
                                    <a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-telephone"></i>
                                <div>
                                    <strong>Téléphone</strong>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $footerTel) }}">{{ $footerTel }}</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-geo-alt"></i>
                                <div>
                                    <strong>Adresse</strong>
                                    <span>{{ $footerAdresse }}</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-clock"></i>
                                <div>
                                    <strong>Horaires</strong>
                                    <span>{{ $footerHoraires }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container footer-bottom-inner">
                <p class="mb-0">&copy; {{ date('Y') }} <strong>{{ $footerNom }}</strong>. Tous droits réservés.</p>
                <div class="footer-bottom-links">
                    <a href="{{ route('confidentialite') }}">Politique de confidentialité</a>
                    <a href="{{ route('cgu') }}">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Boutons flottants -->
    <button id="backToTop" class="back-to-top-btn" type="button" title="Retour en haut" aria-label="Retour en haut">
        <i class="bi bi-arrow-up"></i>
    </button>

    <div class="whatsapp-float">
        <a href="https://wa.me/{{ $footerWhatsapp }}?text=Bonjour,%20j'ai%20besoin%20d'aide%20avec%20{{ urlencode($footerNom) }}"
            target="_blank" rel="noopener" class="whatsapp-btn" title="Contactez-nous sur WhatsApp" aria-label="Contactez-nous sur WhatsApp">
            <i class="bi bi-whatsapp"></i>
            <span class="whatsapp-text">Besoin d'aide ?</span>
        </a>
    </div>


    @guest
        <!-- Modal "connexion requise" partagée (aperçus réservés aux connectés) : placée en fin de <body>
             pour ne jamais être piégée dans un contexte d'empilement d'une section parente. -->
        <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginRequiredModalLabel"><i class="bi bi-lock me-2" style="color: var(--ms-blue);"></i>Connexion requise</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Créez un compte ou connectez-vous pour voir l'aperçu de ce document — c'est gratuit et ça ne prend qu'une minute.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="{{ route('user.registerForm') }}" class="btn btn-outline-primary">Créer un compte</a>
                        <a href="{{ route('user.loginForm') }}" class="btn btn-warning">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bandeau infos flash : fermeture (mémorisée pour la session) + rotation des annonces -->
    <script>
        (function () {
            const banner = document.getElementById('infoFlashBanner');
            if (!banner) return;

            if (sessionStorage.getItem('infoFlashClosed') === '1') {
                banner.remove();
                return;
            }

            banner.addEventListener('click', function (e) {
                if (!e.target.closest('.info-flash-close')) return;
                banner.remove();
                sessionStorage.setItem('infoFlashClosed', '1');
            });

            const items = banner.querySelectorAll('.info-flash-item');
            if (items.length > 1) {
                const isMobile = () => window.matchMedia('(max-width: 767.98px)').matches;
                const PAUSE_BEFORE_NEXT_MIN = 5000; // attendre 5 à 10s avant de passer au suivant
                const PAUSE_BEFORE_NEXT_MAX = 10000;
                const pauseBeforeNext = () => PAUSE_BEFORE_NEXT_MIN + Math.random() * (PAUSE_BEFORE_NEXT_MAX - PAUSE_BEFORE_NEXT_MIN);
                let index = 0;

                function playItem() {
                    const current = items[index];
                    const inner = current.querySelector('.info-flash-message-inner');

                    // Sur mobile : si le texte dépasse, on le fait défiler avant de passer au suivant.
                    if (inner) {
                        inner.classList.remove('is-scrolling');
                        inner.style.transitionDuration = '';
                        inner.style.removeProperty('--info-flash-scroll-distance');
                    }

                    if (isMobile() && inner) {
                        const messageBox = current.querySelector('.info-flash-message');
                        const overflow = inner.scrollWidth - messageBox.clientWidth;

                        if (overflow > 4) {
                            const duration = Math.min(Math.max(overflow / 40, 3), 14); // ~40px/s, entre 3 et 14s
                            const startDelay = 900; // laisser le temps de lire le début avant de défiler

                            setTimeout(function () {
                                inner.style.setProperty('--info-flash-scroll-distance', (-overflow) + 'px');
                                inner.style.transitionDuration = duration + 's';
                                inner.classList.add('is-scrolling');
                            }, startDelay);

                            scheduleNext(startDelay + duration * 1000 + pauseBeforeNext());
                            return;
                        }
                    }

                    scheduleNext(pauseBeforeNext());
                }

                function scheduleNext(delay) {
                    setTimeout(function () {
                        items[index].classList.remove('is-active');
                        index = (index + 1) % items.length;
                        items[index].classList.add('is-active');
                        playItem();
                    }, delay);
                }

                playItem();
            }
        })();
    </script>

    @stack('scripts')

    <script>
        // Validation Bootstrap : bloque l'envoi des formulaires .needs-validation invalides
        (function() {
            'use strict'

            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Libellé accessible du bouton menu + bouton "retour en haut" (visible après 600px de défilement)
        document.addEventListener('DOMContentLoaded', function() {
            const menu = document.getElementById('mainNavbar');
            const toggler = document.querySelector('.navbar-toggler');
            if (menu && toggler) {
                menu.addEventListener('show.bs.collapse', () => toggler.setAttribute('aria-label', 'Fermer le menu'));
                menu.addEventListener('hide.bs.collapse', () => toggler.setAttribute('aria-label', 'Ouvrir le menu'));
            }

            // Listes d'étiquettes repliables (niveaux d'un cycle, matières)
            document.addEventListener('click', function (e) {
                const toggle = e.target.closest('[data-tag-toggle]');
                if (!toggle) return;
                const list = toggle.previousElementSibling;
                const collapsed = list.classList.toggle('is-collapsed');
                toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                toggle.textContent = collapsed ? toggle.dataset.labelMore : toggle.dataset.labelLess;
            });

            const backToTopBtn = document.getElementById('backToTop');
            if (!backToTopBtn) return;

            window.addEventListener('scroll', function() {
                backToTopBtn.classList.toggle('show', window.scrollY > 600);
            }, { passive: true });

            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>

</body>

</html>