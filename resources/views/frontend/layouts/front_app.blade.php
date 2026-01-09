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

    <!-- Styles pour Footer et Boutons Flottants -->
    <style>
        /* === FOOTER MODERNE === */
        .modern-footer {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .modern-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #f7dc6f);
        }

        .footer-main {
            padding: 4rem 0 2rem 0;
            position: relative;
        }

        .footer-section {
            height: 100%;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .footer-logo-img {
            height: 50px;
            width: auto;
            filter: brightness(1.2);
        }

        .footer-brand-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
        }

        .footer-description {
            color: #e1e8f7;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .footer-stats {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #a8c8ff;
            font-size: 0.9rem;
        }

        .stat-item i {
            color: #4ecdc4;
        }

        .footer-title {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: #4ecdc4;
            border-radius: 1px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #e1e8f7;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: #4ecdc4;
            transform: translateX(5px);
        }

        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .contact-item i {
            color: #4ecdc4;
            font-size: 1.2rem;
            margin-top: 0.2rem;
            flex-shrink: 0;
        }

        .contact-item div {
            flex: 1;
        }

        .contact-item strong {
            display: block;
            color: #ffffff;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .contact-item a {
            color: #e1e8f7;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-item a:hover {
            color: #4ecdc4;
        }

        .contact-item span {
            color: #e1e8f7;
        }

        .footer-social {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            justify-content: md-end;
        }

        .social-link {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .social-link:hover::before {
            transform: scale(1);
        }

        .social-link i {
            font-size: 1.2rem;
            color: #ffffff;
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .social-link:hover i {
            transform: scale(1.2);
        }

        .social-link.facebook {
            border: 2px solid #1877f2;
        }

        .social-link.facebook::before {
            background: #1877f2;
        }

        .social-link.whatsapp {
            border: 2px solid #25d366;
        }

        .social-link.whatsapp::before {
            background: #25d366;
        }

        .social-link.instagram {
            border: 2px solid #e4405f;
        }

        .social-link.instagram::before {
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }

        .social-link.twitter {
            border: 2px solid #1da1f2;
        }

        .social-link.twitter::before {
            background: #1da1f2;
        }

        .social-link.linkedin {
            border: 2px solid #0a66c2;
        }

        .social-link.linkedin::before {
            background: #0a66c2;
        }

        .social-link.youtube {
            border: 2px solid #ff0000;
        }

        .social-link.youtube::before {
            background: #ff0000;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.2);
            padding: 1rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom-links {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            justify-content: md-end;
            flex-wrap: wrap;
        }

        .footer-bottom-links a {
            color: #e1e8f7;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: #4ecdc4;
        }

        /* === BOUTON BACK TO TOP === */
        .back-to-top-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transform: scale(0);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-to-top-btn.show {
            transform: scale(1);
        }

        .back-to-top-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .back-to-top-btn:active {
            transform: scale(0.95);
        }

        /* === BOUTON WHATSAPP FLOTTANT === */
        .whatsapp-float {
            position: fixed;
            bottom: 100px;
            right: 30px;
            z-index: 1000;
        }

        .whatsapp-btn {
            display: flex;
            align-items: center;
            background: #25d366;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: pulse-whatsapp 2s infinite;
            gap: 0.75rem;
            min-width: 60px;
            justify-content: center;
        }

        .whatsapp-btn i {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .whatsapp-text {
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            opacity: 0;
            max-width: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .whatsapp-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .whatsapp-btn:hover .whatsapp-text {
            opacity: 1;
            max-width: 120px;
            margin-left: 0.5rem;
        }

        @keyframes pulse-whatsapp {
            0% {
                box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3), 0 0 0 0 rgba(37, 211, 102, 0.7);
            }

            70% {
                box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3), 0 0 0 10px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3), 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .footer-main {
                padding: 3rem 0 1.5rem 0;
            }

            .social-links {
                justify-content: center;
            }

            .footer-bottom-links {
                justify-content: center;
                margin-top: 1rem;
            }

            .back-to-top-btn {
                width: 50px;
                height: 50px;
                bottom: 20px;
                right: 20px;
            }

            .whatsapp-float {
                bottom: 80px;
                right: 20px;
            }

            .whatsapp-btn {
                padding: 0.65rem;
            }

            .whatsapp-text {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .footer-stats {
                flex-direction: column;
                gap: 0.5rem;
            }

            .contact-item {
                gap: 0.75rem;
            }

            .social-links {
                gap: 0.75rem;
            }

            .footer-bottom-links {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }
        }
    </style>

    @stack('styles')

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow">
        <div class="container">
            <!-- Logo seul avec animations améliorées -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('accueil') }}">
                <img src="{{ asset('frontend/img/logo.png') }}" alt="Logo MaxiSujets" class="logo-animate">
            </a>

            <!-- Hamburger menu amélioré -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <div class="hamburger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0"> <!-- Utiliser mx-auto pour centrer -->
                    <li class="nav-item"><a class="nav-link" href="{{ route('accueil') }}"> <i
                                class="bi bi-house-door"></i> Accueil</a></li>
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('sujet.front.index') }}">Liste des
                            sujets</a>
                    </li>

                    <!-- Actualités -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="actualitesDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-newspaper me-1"></i>Actualités & Conseils
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('actualites.index') }}">
                                    <i class="bi bi-newspaper me-2"></i>Actualités
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('astuces-conseils.index') }}">
                                    <i class="bi bi-lightbulb me-2"></i>Astuces & Conseils
                                </a>
                            </li>

                        </ul>
                    </li>

                    {{-- <li class="nav-item"><a class="nav-link" href="#">Forum</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Quizs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Librairie</a></li> --}}
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
    @yield('content')

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
                                    <span>+1000 Documents</span>
                                </div>
                                <div class="stat-item">
                                    <i class="bi bi-people"></i>
                                    <span>+5000 Étudiants</span>
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
                            <a href="#" class="social-link facebook" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://wa.me/22525220020777" class="social-link whatsapp" title="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="#" class="social-link instagram" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="social-link twitter" title="Twitter">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="social-link linkedin" title="LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a href="#" class="social-link youtube" title="YouTube">
                                <i class="bi bi-youtube"></i>
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
                            <a href="#">Politique de confidentialité</a>
                            <a href="#">Conditions d'utilisation</a>
                            <a href="#">FAQ</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script d'animations navbar -->
    <script src="{{ asset('frontend/js/navbar-animations.js') }}"></script>

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
