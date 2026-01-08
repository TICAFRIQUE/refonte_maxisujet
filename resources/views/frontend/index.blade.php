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
            /* Design moderne et épuré - Couleurs du logo MaxiSujets */
            .modern-hero {
                background: linear-gradient(135deg, #ff6b35 0%, #f7931e 25%, #1e3a8a 75%, #0f172a 100%);
                min-height: 65vh;
                display: flex;
                align-items: center;
                position: relative;
                overflow: hidden;
            }

            .modern-hero::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
                animation: rotate 30s linear infinite;
            }

            @keyframes rotate {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            .feature-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                border: none;
                height: 100%;
            }

            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            }

            .document-card {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                transition: all 0.3s ease;
                border: none;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            }

            .document-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }

            .document-preview {
                height: 200px;
                background: linear-gradient(45deg, #f8f9fa, #e9ecef);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            .document-preview::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.2) 50%, transparent 70%);
                animation: shine 3s infinite;
            }

            @keyframes shine {
                0% {
                    transform: translateX(-100%);
                }

                100% {
                    transform: translateX(100%);
                }
            }

            .category-badge {
                background: linear-gradient(45deg, #ff6b35, #f7931e);
                color: white;
                border: none;
                padding: 0.3rem 0.8rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 500;
            }

            .level-badge {
                background: rgba(247, 147, 30, 0.1);
                color: #f7931e;
                border: 1px solid rgba(247, 147, 30, 0.3);
            }

            .subject-badge {
                background: rgba(30, 58, 138, 0.1);
                color: #1e3a8a;
                border: 1px solid rgba(30, 58, 138, 0.3);
            }

            .modern-btn {
                background: linear-gradient(45deg, #ff6b35, #f7931e);
                border: none;
                border-radius: 25px;
                padding: 0.8rem 2rem;
                color: white;
                font-weight: 500;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }

            .modern-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(255, 107, 53, 0.4);
                color: white;
                background: linear-gradient(45deg, #f7931e, #ff6b35);
            }

            .search-section {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-radius: 15px;
                padding: 2rem;
                margin-top: 2rem;
            }

            .stats-number {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(45deg, #ff6b35, #f7931e);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .fade-in {
                opacity: 0;
                transform: translateY(20px);
                animation: fadeInUp 0.8s ease forwards;
            }

            .fade-in-delay-1 {
                animation-delay: 0.2s;
            }

            .fade-in-delay-2 {
                animation-delay: 0.4s;
            }

            .fade-in-delay-3 {
                animation-delay: 0.6s;
            }

            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Boutons avec couleurs du logo */
            .btn-primary {
                background: linear-gradient(45deg, #1e3a8a, #0f172a);
                border: none;
            }

            .btn-primary:hover {
                background: linear-gradient(45deg, #0f172a, #1e3a8a);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(30, 58, 138, 0.3);
            }

            .btn-outline-primary {
                border: 2px solid #1e3a8a;
                color: #1e3a8a;
            }

            .btn-outline-primary:hover {
                background: #1e3a8a;
                border-color: #1e3a8a;
                color: white;
            }

            .btn-warning {
                background: linear-gradient(45deg, #ff6b35, #f7931e);
                border: none;
                color: white;
            }

            .btn-warning:hover {
                background: linear-gradient(45deg, #f7931e, #ff6b35);
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
            }
        </style>
    @endpush

@section('content')
    <!-- Hero Section Moderne -->
    <section class="modern-hero text-white">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4 fade-in">
                        Excellez dans vos <span class="text-warning">Études</span>
                    </h1>
                    <p class="lead mb-4 fade-in-delay-1">
                        Accédez à la plus grande bibliothèque de documents éducatifs de Côte d'Ivoire.
                        Plus de <strong>10,000 ressources</strong> pour votre réussite scolaire et universitaire.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 mb-4 fade-in-delay-2">
                        <a href="{{ route('sujet.front.index') }}" class="btn btn-light btn-lg rounded-pill px-4">
                            <i class="bi bi-search me-2"></i>Explorer Maintenant
                        </a>
                        <a href="{{ route('user.registerForm') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <i class="bi bi-person-plus me-2"></i>Rejoindre Gratuitement
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 fade-in-delay-3">
                    <div class="search-section">
                        <h4 class="mb-3">Recherche Rapide</h4>
                        <form action="{{ route('sujet.front.index') }}" method="GET" class="row g-2">
                            <div class="col-md-8">
                                <input type="text" name="search" class="form-control form-control-lg"
                                    placeholder="Rechercher un document..." style="border-radius: 10px;">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-warning btn-lg w-100" style="border-radius: 10px;">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                        <div class="mt-3">
                            <small class="text-white-50">Recherches populaires :</small>
                            <div class="mt-2">
                                <a href="{{ route('sujet.front.index', ['search' => 'mathématiques']) }}"
                                    class="badge bg-light text-dark me-2">Mathématiques</a>
                                <a href="{{ route('sujet.front.index', ['search' => 'physique']) }}"
                                    class="badge bg-light text-dark me-2">Physique</a>
                                <a href="{{ route('sujet.front.index', ['search' => 'français']) }}"
                                    class="badge bg-light text-dark">Français</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistiques Modernes -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <i class="bi bi-file-earmark-text text-primary mb-3" style="font-size: 2rem;"></i>
                    <div style="font-size: 2.5rem; font-weight: 700; background: linear-gradient(45deg, #ff6b35, #f7931e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">+10K</div>
                    <p class="text-muted fw-bold">Documents</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="bi bi-people text-success mb-3" style="font-size: 2rem;"></i>
                    <div style="font-size: 2.5rem; font-weight: 700; background: linear-gradient(45deg, #ff6b35, #f7931e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">+5K</div>
                    <p class="text-muted fw-bold">Étudiants</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="bi bi-book text-warning mb-3" style="font-size: 2rem;"></i>
                    <div style="font-size: 2.5rem; font-weight: 700; background: linear-gradient(45deg, #ff6b35, #f7931e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">+20</div>
                    <p class="text-muted fw-bold">Matières</p>
                </div>
                <div class="col-md-3 mb-4">
                    <i class="bi bi-shield-check text-info mb-3" style="font-size: 2rem;"></i>
                    <div style="font-size: 2.5rem; font-weight: 700; background: linear-gradient(45deg, #ff6b35, #f7931e); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">100%</div>
                    <p class="text-muted fw-bold">Vérifiés</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Actions Principales -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Comment Ça Marche ?</h2>
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
                <h2 class="display-5 fw-bold text-dark">📰 Actualités</h2>
                <p class="lead text-muted">Restez informé des dernières nouvelles éducatives</p>
            </div>
            <div class="row g-4">
                @foreach(app('App\Http\Controllers\Frontend\RubriqueFrontController')->getActualitesRecentes(3) as $actualite)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.08)'">
                            @if($actualite->getFirstMediaUrl('image_principale'))
                                <img src="{{ $actualite->getFirstMediaUrl('image_principale', 'medium') }}" 
                                     class="card-img-top" alt="{{ $actualite->titre }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient" 
                                     style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="bi bi-newspaper text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-primary rounded-pill">Actualité</span>
                                    <small class="text-muted">{{ $actualite->date_publication ? $actualite->date_publication->format('d M Y') : $actualite->created_at->format('d M Y') }}</small>
                                </div>
                                <h5 class="card-title fw-bold mb-3">{{ Str::limit($actualite->titre, 50) }}</h5>
                                @if($actualite->resume)
                                    <p class="card-text text-muted mb-3">{{ Str::limit($actualite->resume, 100) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('rubrique.show', $actualite->slug) }}" 
                                       class="btn btn-outline-primary rounded-pill px-4">
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
                <a href="{{ route('actualites.index') }}" class="btn btn-primary btn-lg px-5 rounded-pill">
                    <i class="bi bi-newspaper me-2"></i>Voir toutes les actualités
                </a>
            </div>
        </div>
    </section>

    <!-- Section Astuces & Conseils -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">💡 Astuces & Conseils</h2>
                <p class="lead text-muted">Découvrez nos conseils pour réussir vos études</p>
            </div>
            <div class="row g-4">
                @foreach(app('App\Http\Controllers\Frontend\RubriqueFrontController')->getAstucesConseils(3) as $astuce)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.08)'">
                            @if($astuce->getFirstMediaUrl('image_principale'))
                                <img src="{{ $astuce->getFirstMediaUrl('image_principale', 'medium') }}" 
                                     class="card-img-top" alt="{{ $astuce->titre }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient" 
                                     style="height: 200px; background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);">
                                    <i class="bi bi-lightbulb text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-warning text-dark rounded-pill">Conseil</span>
                                    <small class="text-muted">{{ $astuce->date_publication ? $astuce->date_publication->format('d M Y') : $astuce->created_at->format('d M Y') }}</small>
                                </div>
                                <h5 class="card-title fw-bold mb-3">{{ Str::limit($astuce->titre, 50) }}</h5>
                                @if($astuce->resume)
                                    <p class="card-text text-muted mb-3">{{ Str::limit($astuce->resume, 100) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('rubrique.show', $astuce->slug) }}" 
                                       class="btn btn-outline-warning rounded-pill px-4">
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
                <a href="{{ route('astuces-conseils.index') }}" class="btn btn-warning btn-lg px-5 rounded-pill text-dark">
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
    <section class="py-5"
        style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 25%, #1e3a8a 75%, #0f172a 100%);">
        <div class="container text-center text-white">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">Rejoignez la Communauté MaxiSujets</h2>
                    <p class="lead mb-5">Plus de 5,000 étudiants nous font confiance pour leur réussite académique</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('user.registerForm') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill">
                            <i class="bi bi-rocket-takeoff me-2"></i>Commencer Maintenant
                        </a>
                        <a href="{{ route('sujet.front.index') }}"
                            class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                            <i class="bi bi-collection me-2"></i>Parcourir la Bibliothèque
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
