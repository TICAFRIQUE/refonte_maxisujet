<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\sujets\index.blade.php -->
@extends('frontend.layouts.front_app')

@section('content')

<style>
    @media (max-width: 991.98px) {
        .sidebar-responsive {
            order: 2 !important;
            margin-top: 2rem;
        }
        .main-content-responsive {
            order: 1 !important;
        }
    }
    @media (min-width: 992px) {
        .sidebar-responsive {
            order: 1 !important;
        }
        .main-content-responsive {
            order: 2 !important;
        }
    }

    /* Améliorations simples pour les cartes de sujets */
    .subject-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .subject-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .subject-image {
        transition: transform 0.3s ease;
        border-radius: 10px;
        border: 2px solid #f8f9fa;
    }

    .subject-card:hover .subject-image {
        transform: scale(1.05);
        border-color: #ff6b35;
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

    .badge-matiere { 
        background: #e2e8f0; 
        color: #475569;
    }
    .badge-niveau { 
        background: #f1f5f9; 
        color: #64748b;
    }
    .badge-annee { 
        background: #fef3c7; 
        color: #d97706;
    }
    .badge-categorie { 
        background: #dcfce7; 
        color: #16a34a;
    }
    .badge-code { 
        background: #1e293b; 
        color: white;
    }

    .btn-detail {
        background: #64748b;
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        background: #475569;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(100, 116, 139, 0.3);
        color: white;
    }

    .btn-download {
        background: #ff6b35;
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        background: #e55a2b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        color: white;
    }

    .publication-date {
        background: #f8fafc;
        color: #64748b;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid #e2e8f0;
    }

    /* Sidebar simple */
    .simple-sidebar {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filter-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .filter-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .filter-title {
        color: #1e293b;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .filter-title i {
        margin-right: 0.5rem;
        color: #64748b;
    }

    .filter-link {
        display: inline-block;
        padding: 0.4rem 1rem;
        margin: 0.2rem 0.3rem 0.2rem 0;
        background: #f8fafc;
        color: #475569;
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .filter-link:hover {
        background: #e2e8f0;
        color: #334155;
        text-decoration: none;
    }

    .filter-link.active {
        background: #ff6b35;
        color: white;
        border-color: #ff6b35;
    }

    .search-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .search-title {
        color: #1e293b;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .search-title i {
        margin-right: 0.5rem;
        color: #64748b;
    }
</style>
    <div class="container mt-4">
        <!-- Breadcrumb simplifié -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light rounded p-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}" class="text-decoration-none">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Sujets
                </li>
            </ol>
        </nav>
        <div class="row">
            <!-- Recherche -->
            <div class="col-12">
                <div class="search-section" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border: 2px solid #e2e8f0; position: relative; overflow: hidden;">
                    <div class="search-title position-relative" style="background: linear-gradient(90deg, #4f46e5, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 1.3rem; font-weight: 700;">
                        <i class="bi bi-search" style="color: #4f46e5; font-size: 1.2rem; margin-right: 0.8rem; filter: drop-shadow(0 2px 4px rgba(79, 70, 229, 0.3));"></i>
                        Rechercher un sujet
                    </div>
                    <form class="row g-3 position-relative" method="GET" action="{{ route('sujet.front.index') }}">
                        <div class="col-lg-3 col-md-4">
                            <select class="form-select" name="matiere" style="border-radius: 12px; border: 2px solid #e2e8f0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); transition: all 0.3s ease; font-weight: 500;" onmouseover="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.1)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <option value="">🎓 Toutes les matières</option>
                                @foreach ($matieres as $matiere)
                                    <option value="{{ $matiere->slug }}" {{ request('matiere') == $matiere->slug ? 'selected' : '' }}>
                                        {{ $matiere->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <select class="form-select" name="niveau" style="border-radius: 12px; border: 2px solid #e2e8f0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); transition: all 0.3s ease; font-weight: 500;" onmouseover="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.1)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <option value="">📚 Tous les niveaux</option>
                                @foreach ($data_niveaux as $cycle)
                                    <optgroup label="{{ $cycle->libelle }}">
                                        @foreach ($cycle->children as $niveau)
                                            <option value="{{ $niveau->slug }}" {{ request('niveau') == $niveau->slug ? 'selected' : '' }}>
                                                {{ $niveau->libelle }}
                                            </option>
                                            @if ($niveau->children && $niveau->children->count())
                                                @foreach ($niveau->children as $subNiveau)
                                                    <option value="{{ $subNiveau->slug }}" {{ request('niveau') == $subNiveau->slug ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;{{ $subNiveau->libelle }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <select class="form-select" name="annee" style="border-radius: 12px; border: 2px solid #e2e8f0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); transition: all 0.3s ease; font-weight: 500;" onmouseover="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.1)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <option value="">📅 Toutes les années</option>
                                @for ($year = date('Y'); $year >= 2000; $year--)
                                    <option value="{{ $year }}" {{ request('annee') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <select class="form-select" name="categorie" style="border-radius: 12px; border: 2px solid #e2e8f0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); transition: all 0.3s ease; font-weight: 500;" onmouseover="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.1)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <option value="">🏷️ Toutes les catégories</option>
                                @foreach ($categories as $categorie)
                                    <option value="{{ $categorie->slug }}" {{ request('categorie') == $categorie->slug ? 'selected' : '' }}>
                                        {{ $categorie->libelle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control" name="code" value="{{ request('code') }}" placeholder="🔍 Code" style="border-radius: 12px; border: 2px solid #e2e8f0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); transition: all 0.3s ease; font-weight: 500;" onmouseover="this.style.borderColor='#4f46e5'; this.style.boxShadow='0 0 0 3px rgba(79, 70, 229, 0.1)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <button type="submit" class="btn" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none; border-radius: 12px; color: white; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); min-width: 50px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(79, 70, 229, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(79, 70, 229, 0.3)'">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar simple -->
            <div class="col-lg-3 sidebar-responsive">
                <div class="simple-sidebar">
                    <!-- Matières -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="bi bi-book"></i>
                            Matières
                        </div>
                        <div>
                            @foreach ($data_matieres as $matiere)
                                <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['matiere' => $matiere->slug])) }}"
                                    class="filter-link {{ request('matiere') == $matiere->slug ? 'active' : '' }}">
                                    {{ $matiere->libelle }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Catégories -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="bi bi-tags"></i>
                            Catégories
                        </div>
                        <div>
                            @foreach ($data_categories as $categorie)
                                <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['categorie' => $categorie->slug])) }}"
                                    class="filter-link {{ request('categorie') == $categorie->slug ? 'active' : '' }}">
                                    {{ $categorie->libelle }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Niveaux simplifiés -->
                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="bi bi-mortarboard"></i>
                            Niveaux
                        </div>
                        <div>
                            @foreach ($data_niveaux as $cycle)
                                @foreach ($cycle->children as $niveau)
                                    <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['niveau' => $niveau->slug])) }}"
                                        class="filter-link {{ request('niveau') == $niveau->slug ? 'active' : '' }}">
                                        {{ $niveau->libelle }}
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="col-lg-9 main-content-responsive">
                <!-- Liste des sujets améliorée -->
                <div class="row g-4">
                    @forelse($sujets as $sujet)
                        <div class="col-md-6 col-xl-6">
                            <div class="card subject-card h-100">
                                <div class="card-body p-4">
                                    <!-- En-tête avec image et titre -->
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            @php
                                                $fileUrl = $sujet->getFirstMediaUrl('non_corrige');
                                                $isPdf = $fileUrl && Str::endsWith($fileUrl, '.pdf');
                                            @endphp
                                            @if($isPdf)
                                                <img src="{{ asset('frontend/img/pdf-icon.png') }}" alt="PDF"
                                                     class="subject-image" style="width:60px; height:60px; object-fit:cover;">
                                            @elseif($fileUrl)
                                                <img src="{{ $fileUrl }}" alt="Aperçu"
                                                     class="subject-image" style="width:60px; height:60px; object-fit:cover;">
                                            @else
                                                <div class="subject-image d-flex align-items-center justify-content-center bg-light" 
                                                     style="width:60px; height:60px;">
                                                    <i class="bi bi-file-earmark-text text-muted" style="font-size: 1.5rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="subject-title mb-0 me-2">{{ Str::limit($sujet->libelle, 40) }}</h6>
                                                <span class="modern-badge badge-code">{{ $sujet->code }}</span>
                                            </div>
                                            <p class="subject-description mb-0 small">
                                                {{ Str::limit($sujet->description, 80) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Informations organisées -->
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
                                                    @if($sujet->niveaux->count() > 0)
                                                        <span class="modern-badge badge-niveau">{{ $sujet->niveaux->first()->libelle }}</span>
                                                        @if($sujet->niveaux->count() > 1)
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

                                    <!-- Date de publication -->
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            Publié le {{ $sujet->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>

                                    <!-- Boutons d'action améliorés -->
                                    <div class="d-flex flex-wrap gap-2 mt-auto">
                                        <a href="{{ route('sujet.front.show', $sujet->libelle) }}"
                                            class="btn btn-primary btn-sm flex-fill position-relative overflow-hidden" 
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                            <i class="bi bi-eye me-2"></i>
                                            <span>Voir détails</span>
                                            <div class="position-absolute top-0 start-0 w-100 h-100" 
                                                 style="background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%); pointer-events: none;"></div>
                                        </a>

                                        <!-- Bouton de téléchargement avec gestion des points -->
                                        {{-- @auth
                                            @if (auth()->user()->points > 0)
                                                <div class="btn-group flex-fill" role="group">
                                                    @if ($sujet->getFirstMediaUrl('non_corrige'))
                                                        <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'non_corrige']) }}"
                                                            target="_blank" class="btn btn-primary btn-sm">
                                                            <i class="bi bi-download me-1"></i>Sujet
                                                        </a>
                                                    @endif
                                                    @if ($sujet->getFirstMediaUrl('corrige'))
                                                        <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'corrige']) }}"
                                                            target="_blank" class="btn btn-success btn-sm">
                                                            <i class="bi bi-download me-1"></i>Corrigé
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <button class="btn btn-outline-warning btn-sm flex-fill" disabled>
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    Points insuffisants
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                                <i class="bi bi-lock me-1"></i>
                                                Se connecter
                                            </a>
                                        @endauth --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">Aucun sujet trouvé pour ces critères.</div>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4">
                    {{ $sujets->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#matiere-select').select2({
                    placeholder: "Sélectionnez une matière",
                    allowClear: true
                });
                $('#niveau-select').select2({
                    placeholder: "Sélectionnez un niveau",
                    allowClear: true
                });
                $('#annee-select').select2({
                    placeholder: "Sélectionnez une année",
                    allowClear: true
                });
                $('#categorie-select').select2({
                    placeholder: "Sélectionnez une catégorie",
                    allowClear: true
                });
            });
        </script>
    @endpush
@endsection


