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
    {{-- <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "MaxiSujets",
        "description": "Plateforme de téléchargement de documents éducatifs gratuits",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('frontend/images/logo.png') }}",
        "sameAs": [
            "https://facebook.com/maxisujets",
            "https://twitter.com/maxisujets",
            "https://instagram.com/maxisujets"
        ],
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "CI"
        }
    }
    </script> --}}
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
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    @stack('styles')

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('accueil') }}">
                <img src="{{ asset('frontend/img/logo.png') }}" alt="Logo MaxiSujets" style="height: 40px; margin-right: 8px;">
                <i class="bi bi-mortarboard-fill"></i> 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0"> <!-- Utiliser mx-auto pour centrer -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('accueil') }}"> <i class="bi bi-house-door"></i> Accueil</a></li>
                    <!-- Catégories -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="catDropdown" role="button"
                            data-bs-toggle="dropdown">
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('sujet.front.index') }}">Sujets</a></li>

                    <li class="nav-item"><a class="nav-link" href="#">Actualités</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Forum</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Quizs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Librairie</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    @guest
                        <a href="{{ route('user.loginForm') }}" class="btn btn-outline-light me-2">Connexion</a>
                        <a href="{{ route('user.registerForm') }}" class="btn btn-warning">S’inscrire</a>
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
                                <li><hr class="dropdown-divider"></li>
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
    @yield('content')

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('accueil') }}">Accueil</a></li>
                        <li><a href="{{ route('sujet.front.index') }}">Supports</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Coordonnées</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope"></i> info@maxisujets.net</li>
                        <li><i class="bi bi-telephone"></i> (+225) 25 22 00 20 77</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Réseaux sociaux</h5>
                    <a href="#" class="me-2"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="me-2"><i class="bi bi-whatsapp fs-4"></i></a>
                    <a href="#" class="me-2"><i class="bi bi-instagram fs-4"></i></a>
                </div>
            </div>
            <div class="text-center mt-3">
                &copy; {{ date('Y') }} MaxiSujets. Tous droits réservés.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    </script>

</body>

</html>
