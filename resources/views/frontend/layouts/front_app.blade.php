<!-- filepath: resources/views/frontend/index.blade.php -->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'MaxiSujets - Plateforme Éducative de Documents Scolaires et Universitaires')</title>
    <meta name="description" content="@yield('meta_description', 'MaxiSujets - Téléchargez des milliers de documents éducatifs : cours, exercices, examens, concours. Ressources gratuites pour élèves, étudiants et enseignants.')">
    <meta name="keywords" content="@yield('meta_keywords', 'documents scolaires, cours gratuits, exercices, examens, concours, ressources éducatives, téléchargement, étudiant, élève, enseignant, université, lycée, collège')">
    <meta name="author" content="MaxiSujets">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <meta name="language" content="fr">
    <meta name="revisit-after" content="7 days">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', 'MaxiSujets - Plateforme Éducative de Documents Scolaires')">
    <meta property="og:description" content="@yield('og_description', 'Téléchargez des milliers de documents éducatifs gratuitement. Cours, exercices, examens pour tous les niveaux.')">
    <meta property="og:image" content="@yield('og_image', asset('frontend/images/logo-social.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="MaxiSujets">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('twitter_url', url()->current())">
    <meta name="twitter:title" content="@yield('twitter_title', 'MaxiSujets - Documents Éducatifs Gratuits')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Plateforme de téléchargement de documents scolaires et universitaires.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('frontend/images/logo-social.png'))">
    <meta name="twitter:creator" content="@MaxiSujets">
    <meta name="twitter:site" content="@MaxiSujets">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('frontend/images/site.webmanifest') }}">

    <!-- Additional SEO -->
    <meta name="theme-color" content="#0d6efd">
    <meta name="msapplication-TileColor" content="#0d6efd">
    <meta name="application-name" content="MaxiSujets">
    <meta name="msapplication-tooltip" content="Plateforme de documents éducatifs">

    <!-- Schema.org structured data -->
    @php
        $ldJson = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => 'MaxiSujets',
            'description' => 'Plateforme de téléchargement de documents éducatifs gratuits',
            'url' => url('/'),
            'logo' => asset('frontend/images/logo.png'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Abidjan',
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
    <!-- Choices.js CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}?v={{ @filemtime(public_path('frontend/css/style.css')) ?: '1' }}">

    @stack('styles')

</head>

<body>

    <a class="visually-hidden-focusable" href="#main-content">Aller au contenu principal</a>

    @if (isset($info_flashes) && $info_flashes->isNotEmpty())
        <div id="infoFlashBanner" class="info-flash-banner" role="region" aria-label="Annonces">
            @foreach ($info_flashes as $flash)
                <div class="info-flash-item info-flash-{{ $flash->type }} {{ $loop->first ? 'is-active' : '' }}">
                    <div class="info-flash-content">
                        <span class="info-flash-label">
                            <i class="bi bi-megaphone-fill"></i> Info
                        </span>
                        <span class="info-flash-message">{{ $flash->message }}</span>
                        @if ($flash->lien)
                            <a href="{{ $flash->lien }}" class="info-flash-link">{{ $flash->lien_texte ?: 'En savoir plus' }} <i class="bi bi-arrow-right"></i></a>
                        @endif
                    </div>
                </div>
            @endforeach
            <button type="button" class="info-flash-close" id="infoFlashClose" aria-label="Fermer les annonces">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <!-- Logo seul -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('accueil') }}">
                <img src="{{ asset('frontend/img/logo.png') }}" alt="Logo MaxiSujets" class="logo-animate">
            </a>

            <!-- Hamburger menu -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <div class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('accueil') ? 'active' : '' }}" href="{{ route('accueil') }}">Accueil</a>
                    </li>
                    <!-- Catégories -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('sujet.front.*') && request('categorie') ? 'active' : '' }}"
                            href="#" id="catDropdown" role="button" data-bs-toggle="dropdown">
                            Catégories
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($data_categories as $item)
                                <li>
                                    <a class="dropdown-item {{ request('categorie') == $item->slug ? 'text-primary' : '' }}"
                                        href="{{ route('sujet.front.index', ['categorie' => $item->slug]) }}">{{ $item->libelle }}</a>
                                </li>
                            @endforeach
                            <hr class="dropdown-divider">
                            <li>
                                <a class="dropdown-item" href="{{ route('sujet.front.index') }}">Tous les sujets</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sujet.front.*') && !request('categorie') ? 'active' : '' }}"
                            href="{{ route('sujet.front.index') }}">Liste des sujets</a>
                    </li>

                    <!-- Actualités -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs(['actualites.*', 'astuces-conseils.*']) ? 'active' : '' }}"
                            href="#" id="actualitesDropdown" role="button" data-bs-toggle="dropdown">
                            Actualités & Conseils
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('actualites.*') ? 'text-primary' : '' }}" href="{{ route('actualites.index') }}">
                                    <i class="bi bi-newspaper me-2"></i>Actualités
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('astuces-conseils.*') ? 'text-primary' : '' }}" href="{{ route('astuces-conseils.index') }}">
                                    <i class="bi bi-lightbulb me-2"></i>Astuces & Conseils
                                </a>
                            </li>

                        </ul>
                    </li>

                </ul>
                <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('sujet.front.index') }}" class="navbar-search-icon d-none d-lg-inline-flex" title="Rechercher un sujet">
                        <i class="bi bi-search"></i>
                    </a>
                    @guest
                        <a href="{{ route('user.loginForm') }}" class="btn btn-outline-primary ms-2 me-2">Connexion</a>
                        <a href="{{ route('user.registerForm') }}" class="btn btn-warning">S'inscrire</a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center w-100"
                                id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-2"></i>
                                <span>{{ Auth::user()->username ?? Auth::user()->email }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end w-100" aria-labelledby="userMenu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                        <i class="bi bi-person-circle me-2"></i>Mon profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.sujet.index') }}">
                                        <i class="bi bi-file-earmark-plus me-2"></i> Mes sujets
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('user.logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
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
    <!-- End Navbar -->

    <!-- Afficher les messages d'alerte -->
    @include('sweetalert::alert')
    <!--Afficher le contenu spécifique de chaque page -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Footer Moderne -->
    <footer class="modern-footer mt-auto">
        <!-- Footer Principal -->
        <div class="footer-main">
            <div class="container">
                <div class="row g-4">
                    <!-- À propos -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <div class="footer-logo mb-3">
                                <img src="{{ asset('frontend/img/logo.png') }}" alt="MaxiSujets"
                                    class="footer-logo-img">
                                {{-- <span class="footer-brand-name">MaxiSujets</span> --}}
                            </div>
                            <p class="footer-description">
                                Ce site regroupe de nombreux supports de sujets et de cours portant sur divers domaines
                                de votre parcours scolaire, universitaire et votre entrée dans la vie professionnelle.
                            </p>
                            <div class="footer-stats">
                                <div class="stat-item">
                                    <i class="bi bi-file-earmark-text"></i>
                                    <span>{{ number_format($footer_stats['sujets'] ?? 0) }} sujets</span>
                                </div>
                                <div class="stat-item">
                                    <i class="bi bi-people"></i>
                                    <span>{{ number_format($footer_stats['membres'] ?? 0) }} membres</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h5 class="footer-title">Navigation</h5>
                            <ul class="footer-links">
                                <li><a href="{{ route('accueil') }}"><i class="bi bi-house"></i> Accueil</a></li>
                                <li><a href="{{ route('sujet.front.index') }}"><i class="bi bi-files"></i>
                                        Documents</a></li>
                                <li><a href="{{ route('actualites.index') }}"><i class="bi bi-newspaper"></i>
                                        Actualités</a></li>
                                <li><a href="{{ route('astuces-conseils.index') }}"><i class="bi bi-lightbulb"></i>
                                        Conseils</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Catégories Populaires -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h5 class="footer-title">Catégories</h5>
                            <ul class="footer-links">
                                @foreach ($data_categories->take(4) as $category)
                                    <li><a
                                            href="{{ route('sujet.front.index', ['categorie' => $category->slug]) }}">{{ $category->libelle }}</a>
                                    </li>
                                @endforeach
                                <li><a href="{{ route('sujet.front.index') }}"><strong>Voir tout <i
                                                class="bi bi-arrow-right"></i></strong></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Contact & Support -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <h5 class="footer-title">Contact & Support</h5>
                            <div class="footer-contact">
                                <div class="contact-item">
                                    <i class="bi bi-envelope-fill"></i>
                                    <div>
                                        <strong>Email</strong>
                                        <a href="mailto:info@maxisujets.net">info@maxisujets.net</a>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="bi bi-telephone-fill"></i>
                                    <div>
                                        <strong>Téléphone</strong>
                                        <a href="tel:+22525220020777">(+225) 25 22 00 20 77</a>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <div>
                                        <strong>Adresse</strong>
                                        <span>Abidjan, Côte d'Ivoire</span>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="bi bi-clock-fill"></i>
                                    <div>
                                        <strong>Support</strong>
                                        <span>24h/7j disponible</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Réseaux Sociaux -->
        <div class="footer-social">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-3 mb-md-0">Suivez-nous sur les réseaux sociaux</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="social-links">
                            <a href="https://wa.me/22525220020777" class="social-link whatsapp" title="WhatsApp" target="_blank" rel="noopener">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="mailto:info@maxisujets.net" class="social-link email" title="Email">
                                <i class="bi bi-envelope-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">
                            &copy; {{ date('Y') }} <strong>MaxiSujets</strong>. Tous droits réservés.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-bottom-links">
                            <a href="{{ route('confidentialite') }}">Politique de confidentialité</a>
                            <a href="{{ route('cgu') }}">Conditions d'utilisation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Boutons Flottants -->
    <!-- Bouton Back to Top -->
    <button id="backToTop" class="back-to-top-btn" title="Retour en haut">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Bouton WhatsApp Flottant -->
    <div class="whatsapp-float">
        <a href="https://wa.me/22525220020777?text=Bonjour,%20j'ai%20besoin%20d'aide%20avec%20MaxiSujets"
            target="_blank" class="whatsapp-btn" title="Contactez-nous sur WhatsApp">
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

    <!-- Script d'animations navbar -->
    <script src="{{ asset('frontend/js/navbar-animations.js') }}"></script>

    <!-- Bandeau infos flash : fermeture (mémorisée pour la session) + rotation + décalage de la navbar fixe -->
    <script>
        (function () {
            const banner = document.getElementById('infoFlashBanner');
            if (!banner) return;

            if (sessionStorage.getItem('infoFlashClosed') === '1') {
                banner.remove();
                return;
            }

            function majDecalage() {
                document.documentElement.style.setProperty('--info-flash-height', banner.offsetHeight + 'px');
            }
            majDecalage();
            window.addEventListener('resize', majDecalage);

            const closeBtn = document.getElementById('infoFlashClose');
            closeBtn && closeBtn.addEventListener('click', function () {
                banner.remove();
                document.documentElement.style.setProperty('--info-flash-height', '0px');
                sessionStorage.setItem('infoFlashClosed', '1');
            });

            const items = banner.querySelectorAll('.info-flash-item');
            if (items.length > 1) {
                let index = 0;
                setInterval(function () {
                    items[index].classList.remove('is-active');
                    index = (index + 1) % items.length;
                    items[index].classList.add('is-active');
                }, 5000);
            }
        })();
    </script>

    <!-- Scripts d'améliorations pour la page d'accueil -->
    @if (Route::currentRouteName() === 'accueil')
        <script src="{{ asset('frontend/js/home-enhancements.js') }}"></script>
        <script src="{{ asset('frontend/js/modern-animations.js') }}"></script>
    @endif

    @stack('scripts')

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
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

        // === SCRIPT BOUTON BACK TO TOP ===
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopBtn = document.getElementById('backToTop');

            // Afficher/Masquer le bouton selon le scroll
            function toggleBackToTop() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            }

            // Event listener pour le scroll
            window.addEventListener('scroll', toggleBackToTop);

            // Event listener pour le clic du bouton
            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Animation d'entrée des éléments du footer
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observer les sections du footer
            const footerSections = document.querySelectorAll('.footer-section');
            footerSections.forEach(function(section) {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(section);
            });

            // Animation des liens sociaux au hover
            const socialLinks = document.querySelectorAll('.social-link');
            socialLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px) scale(1.1)';
                });

                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Animation des statistiques
            const statItems = document.querySelectorAll('.stat-item');
            let delay = 0;
            statItems.forEach(function(item) {
                setTimeout(function() {
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, delay);
                delay += 200;
            });

            // Style initial pour les statistiques
            statItems.forEach(function(item) {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });
        });
    </script>

</body>

</html>
