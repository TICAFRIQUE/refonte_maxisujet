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

    @push('styles')
        <style>
            /* Hero simple : fond clair uni, pas de dégradé ni d'animation en arrière-plan */
            .modern-hero {
                background: var(--ms-hero-bg);
                min-height: 55vh;
                display: flex;
                align-items: center;
                position: relative;
                overflow: hidden;
            }

            .audience-card {
                border-radius: var(--ms-radius-lg);
                padding: 2rem;
                height: 100%;
                border-top: 4px solid transparent;
            }

            .audience-card.audience-orange {
                background: var(--ms-orange-light);
                border-top-color: var(--ms-orange);
            }

            .audience-card.audience-blue {
                background: var(--ms-blue-light);
                border-top-color: var(--ms-blue);
            }

            .student-illustration { filter: drop-shadow(0 10px 25px rgba(0, 0, 0, 0.15)); }

            @media (max-width: 991.98px) {
                .modern-hero { min-height: auto; padding: 3rem 0; text-align: center; }
                .modern-hero .d-flex.flex-column.flex-sm-row { justify-content: center; }
            }

            .feature-card {
                background: white;
                border-radius: var(--ms-radius-lg);
                padding: 2rem;
                box-shadow: var(--ms-shadow-rest);
                border: 1px solid var(--ms-border-subtle);
                transition: box-shadow 0.2s ease, border-color 0.2s ease;
                height: 100%;
            }

            .feature-card:hover {
                box-shadow: var(--ms-shadow-hover);
                border-color: var(--ms-border);
            }

            .document-card {
                background: white;
                border-radius: var(--ms-radius-lg);
                overflow: hidden;
                border: 1px solid var(--ms-border-subtle);
                box-shadow: var(--ms-shadow-rest);
                transition: box-shadow 0.2s ease, border-color 0.2s ease;
            }

            .document-card:hover {
                box-shadow: var(--ms-shadow-hover);
                border-color: var(--ms-border);
            }

            .document-preview {
                height: 200px;
                background: var(--ms-bg-alt);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            /* 4 cartes par ligne sur la page d'accueil : textes légèrement réduits pour rester lisibles dans des cartes plus étroites. */
            .document-card .card-title {
                font-size: 1rem;
            }

            .document-card p.text-muted {
                font-size: 0.85rem;
            }

            .document-card .category-badge,
            .document-card .subject-badge,
            .document-card .level-badge {
                font-size: 0.7rem;
            }

            .category-badge {
                background: var(--ms-orange-light);
                color: var(--ms-orange-dark);
                border: none;
                padding: 0.3rem 0.8rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .level-badge {
                background: rgba(247, 147, 30, 0.1);
                color: var(--ms-orange-2);
                border: 1px solid rgba(247, 147, 30, 0.3);
            }

            .subject-badge {
                background: var(--ms-blue-light);
                color: var(--ms-blue-dark);
                border: 1px solid rgba(13, 110, 253, 0.2);
            }

            .modern-btn {
                background: var(--ms-orange);
                border: none;
                border-radius: var(--ms-radius-sm);
                padding: 0.8rem 2rem;
                color: white;
                font-weight: 600;
                transition: background 0.2s ease, transform 0.2s ease;
                text-decoration: none;
                display: inline-block;
            }

            .modern-btn:hover {
                background: var(--ms-orange-dark);
                transform: translateY(-1px);
                color: white;
            }

            .news-card { transition: box-shadow 0.2s ease, border-color 0.2s ease; }
            .news-card:hover { box-shadow: var(--ms-shadow-hover); border-color: var(--ms-border); }
        </style>
    @endpush

@section('content')
    <!-- Hero Section : simple, fond clair -->
    <section class="modern-hero">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-bold mb-4" style="color: var(--ms-ink);">
                        Tous les sujets d'examens, au même endroit
                    </h1>
                    <p class="lead mb-4 text-muted" style="font-size: 1.2rem; line-height: 1.6;">
                        Des sujets corrigés, des annales et des épreuves types pour préparer sereinement vos
                        examens et concours — déposés par la communauté, vérifiés par notre équipe.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                        <a href="{{ route('sujet.front.index') }}" class="btn btn-warning btn-lg px-5 py-3">
                            <i class="bi bi-collection-fill me-2"></i>Explorer les sujets
                        </a>
                        <a href="{{ route('user.registerForm') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                            <i class="bi bi-person-plus me-2"></i>Créer un compte gratuit
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="position-relative">
                        <!-- Illustration : fiches et cahier (sujets/corrigés) -->
                        <div class="student-illustration mx-auto" style="width: 300px; max-width: 100%; height: 350px; position: relative;">
                            <!-- Cercle de fond -->
                            <div class="position-absolute w-100 h-100 rounded-circle" style="background: linear-gradient(135deg, rgba(255, 107, 53, 0.12) 0%, rgba(13, 110, 253, 0.12) 100%);"></div>

                            <svg class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 230px; height: 250px;" viewBox="0 0 230 250" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Illustration de fiches et d'un cahier">
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
        </div>
    </section>

    <!-- Deux publics, deux parcours -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="audience-card audience-orange">
                        <h4 class="fw-bold mb-2">Élèves & étudiants</h4>
                        <p class="text-muted mb-4">
                            Téléchargez des sujets corrigés, des annales et des épreuves types pour préparer
                            sereinement vos examens et concours.
                        </p>
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <a href="{{ route('sujet.front.index') }}" class="btn btn-warning">
                                Parcourir les sujets
                            </a>
                            <a href="{{ route('user.registerForm') }}" class="fw-semibold text-decoration-none" style="color: var(--ms-orange-dark);">
                                Créer un compte gratuit <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="audience-card audience-blue">
                        <h4 class="fw-bold mb-2">Enseignants & contributeurs</h4>
                        <p class="text-muted mb-4">
                            Partagez vos sujets avec la communauté, gagnez des points à chaque publication
                            approuvée, et aidez d'autres élèves à réussir.
                        </p>
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <a href="{{ route('user.sujet.create') }}" class="btn btn-primary">
                                Publier un sujet
                            </a>
                            <a href="{{ route('user.registerForm') }}" class="fw-semibold text-decoration-none" style="color: var(--ms-blue-dark);">
                                Créer un compte gratuit <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Actions Principales -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Comment ça marche ?</h2>
                <p class="lead text-muted">Trois étapes simples pour accéder à toutes nos ressources</p>
            </div>
            @include('frontend.sections.carte_avantage_improved')
        </div>
    </section>

    <!-- Documents Récents - Design Moderne -->
    @include('frontend.sections.recent_document')

    <!-- Section Actualités -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">Actualités</h2>
                <p class="lead text-muted">Restez informé des dernières nouvelles éducatives</p>
            </div>
            <div class="row g-4">
                @foreach(app('App\Http\Controllers\frontend\RubriqueFrontController')->getActualitesRecentes(3) as $actualite)
                    <div class="col-lg-4 col-md-6">
                        <div class="card news-card h-100">
                            @if($actualite->getFirstMediaUrl('image_principale'))
                                <img src="{{ $actualite->getFirstMediaUrl('image_principale', 'medium') }}"
                                     class="card-img-top" alt="{{ $actualite->titre }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center"
                                     style="height: 200px; background: var(--ms-gradient-navy);">
                                    <i class="bi bi-newspaper text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge" style="background: var(--ms-blue-light); color: var(--ms-blue-dark);">Actualité</span>
                                    <small class="text-muted">{{ $actualite->date_publication ? $actualite->date_publication->format('d M Y') : $actualite->created_at->format('d M Y') }}</small>
                                </div>
                                <h5 class="card-title mb-3">{{ Str::limit($actualite->titre, 50) }}</h5>
                                @if($actualite->resume)
                                    <p class="card-text text-muted mb-3">{{ Str::limit($actualite->resume, 100) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('rubrique.show', $actualite->slug) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-arrow-right me-1"></i>Lire plus
                                    </a>
                                    <small class="text-muted">
                                        <i class="bi bi-eye me-1"></i>{{ $actualite->nb_vues }} vues
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('actualites.index') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-newspaper me-2"></i>Voir toutes les actualités
                </a>
            </div>
        </div>
    </section>

    <!-- Section Astuces & Conseils -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">Astuces & Conseils</h2>
                <p class="lead text-muted">Découvrez nos conseils pour réussir vos études</p>
            </div>
            <div class="row g-4">
                @foreach(app('App\Http\Controllers\frontend\RubriqueFrontController')->getAstucesConseils(3) as $astuce)
                    <div class="col-lg-4 col-md-6">
                        <div class="card news-card h-100">
                            @if($astuce->getFirstMediaUrl('image_principale'))
                                <img src="{{ $astuce->getFirstMediaUrl('image_principale', 'medium') }}"
                                     class="card-img-top" alt="{{ $astuce->titre }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center"
                                     style="height: 200px; background: var(--ms-gradient-orange);">
                                    <i class="bi bi-lightbulb text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge" style="background: var(--ms-orange-light); color: var(--ms-orange-dark);">Conseil</span>
                                    <small class="text-muted">{{ $astuce->date_publication ? $astuce->date_publication->format('d M Y') : $astuce->created_at->format('d M Y') }}</small>
                                </div>
                                <h5 class="card-title mb-3">{{ Str::limit($astuce->titre, 50) }}</h5>
                                @if($astuce->resume)
                                    <p class="card-text text-muted mb-3">{{ Str::limit($astuce->resume, 100) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('rubrique.show', $astuce->slug) }}"
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-arrow-right me-1"></i>Lire plus
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
            <div class="text-center mt-5">
                <a href="{{ route('astuces-conseils.index') }}" class="btn btn-warning btn-lg px-5 text-dark">
                    <i class="bi bi-lightbulb me-2"></i>Voir toutes les astuces
                </a>
            </div>
        </div>
    </section>

    <!-- Parcourir par Niveau -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Parcourir par Niveau</h2>
                <p class="lead text-muted">Trouvez des ressources adaptées à votre niveau d'études</p>
            </div>
            @include('frontend.components.cycle_niveaux_improved')
        </div>
    </section>

    <!-- Matières Disponibles -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Matières Disponibles</h2>
                <p class="lead text-muted">Explorez nos ressources par matière</p>
            </div>
            @include('frontend.components.matieres_improved')
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-5 bg-white">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4" style="color: var(--ms-ink);">Rejoignez la communauté MaxiSujets</h2>
                    <p class="lead mb-5 text-muted">
                        Inscrivez-vous et gagnez des points dès aujourd'hui pour accéder à nos sujets
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('user.registerForm') }}" class="btn btn-warning btn-lg px-5 py-3">
                            <i class="bi bi-rocket-takeoff me-2"></i>Commencer maintenant
                        </a>
                        <a href="{{ route('sujet.front.index') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                            <i class="bi bi-collection me-2"></i>Parcourir la bibliothèque
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
