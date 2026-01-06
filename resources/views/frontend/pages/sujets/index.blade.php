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
</style>
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded shadow-sm p-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Sujets
                </li>
                @if (request('categorie'))
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ ucfirst(request('categorie')) }}
                    </li>
                @endif
                @if (request('niveau'))
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ ucfirst(request('niveau')) }}
                    </li>
                @endif
                @if (request('matiere'))
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ ucfirst(request('matiere')) }}
                    </li>
                @endif
                @if (request('annee'))
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ request('annee') }}
                    </li>
                @endif
                @if (request('code'))
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ request('code') }}
                    </li>
                @endif
            </ol>
        </nav>
        <div class="row d-flex flex-wrap">
            <!-- Sidebar améliorée avec menu récursif -->
            <div class="col-lg-3 mb-4 sidebar-responsive">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3 text-primary">Cycles & Niveaux</h5>
                        @foreach ($data_niveaux as $cycle)
                            <div class="mb-3">
                                <div class="fw-bold mb-2" style="color:#04f;">
                                    <i class="bi {{ $cycle->icon ?? 'bi-book' }}"></i> {{ $cycle->libelle }}
                                </div>
                                <div class="row g-2">
                                    @foreach ($cycle->children as $niveau)
                                        <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['niveau' => $niveau->slug])) }}"
                                            class="badge text-dark border text-decoration-none {{ request('niveau') == $niveau->slug ? 'bg-success text-white' : 'bg-light' }}">
                                            {{ $niveau->libelle }}
                                        </a>
                                        @if ($niveau->children && $niveau->children->count())
                                            @foreach ($niveau->children as $subNiveau)
                                                <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['niveau' => $subNiveau->slug])) }}"
                                                    class="badge text-dark border ms-2 text-decoration-none {{ request('niveau') == $subNiveau->slug ? 'bg-success text-white' : 'bg-light' }}">
                                                    &raquo; {{ $subNiveau->libelle }}
                                                </a>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        <hr>
                        <h5 class="mb-3 text-primary">Matières</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($data_matieres as $matiere)
                                <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['matiere' => $matiere->slug])) }}"
                                    class="badge text-dark border text-decoration-none {{ request('matiere') == $matiere->slug ? 'bg-success text-white' : 'bg-light' }}">
                                    {{ $matiere->libelle }}
                                </a>
                            @endforeach
                        </div>
                        <hr>
                        <h5 class="mb-3 text-primary">Catégories</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($data_categories as $categorie)
                                <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['categorie' => $categorie->slug])) }}"
                                    class="badge  text-dark px-3 py-2 shadow-sm {{ request('categorie') == $categorie->slug ? 'bg-success text-white' : 'bg-warning' }}">
                                    {{ $categorie->libelle }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <div class="col-lg-9 main-content-responsive">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3 text-primary">Recherche de sujet</h5>
                        <form class="row g-3" method="GET" action="{{ route('sujet.front.index') }}">
                            <div class="col-md-3">
                                <label class="form-label">Matière</label>
                                <select class="form-select select2-custom" name="matiere" id="matiere-select">
                                    <option value="">Toutes</option>
                                    @foreach ($matieres as $matiere)
                                        <option value="{{ $matiere->slug }}"
                                            {{ request('matiere') == $matiere->slug ? 'selected' : '' }}>
                                            {{ $matiere->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Niveau</label>
                                <select class="form-select select2-custom" name="niveau" id="niveau-select">
                                    <option value="">Tous</option>
                                    @foreach ($data_niveaux as $cycle)
                                        <optgroup label="{{ $cycle->libelle }}">
                                            @foreach ($cycle->children as $niveau)
                                                <option value="{{ $niveau->slug }}"
                                                    {{ request('niveau') == $niveau->slug ? 'selected' : '' }}>
                                                    {{ $niveau->libelle }}
                                                </option>
                                                @if ($niveau->children && $niveau->children->count())
                                                    @foreach ($niveau->children as $subNiveau)
                                                        <option value="{{ $subNiveau->slug }}"
                                                            {{ request('niveau') == $subNiveau->slug ? 'selected' : '' }}>
                                                            &nbsp;&nbsp;{{ $subNiveau->libelle }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Année</label>
                                <select class="form-select select2-custom" name="annee" id="annee-select">
                                    <option value="">Toutes</option>
                                    @for ($year = date('Y'); $year >= 2000; $year--)
                                        <option value="{{ $year }}"
                                            {{ request('annee') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select select2-custom" name="categorie" id="categorie-select">
                                    <option value="">Toutes</option>
                                    @foreach ($categories as $categorie)
                                        <option value="{{ $categorie->slug }}"
                                            {{ request('categorie') == $categorie->slug ? 'selected' : '' }}>
                                            {{ $categorie->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" value="{{ request('code') }}"
                                    placeholder="Code">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i>
                                    Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Liste des sujets améliorée -->
                <div class="row g-4">
                    @forelse($sujets as $sujet)
                        <div class="col-md-6 col-xl-4">
                            <div class="card subject-card h-100">
                                <div class="row g-0 align-items-center">
                                    <div class="col-4 text-center">
                                        <div class="p-3">
                                            @php
                                                $fileUrl = $sujet->getFirstMediaUrl('non_corrige');
                                                $isPdf = $fileUrl && Str::endsWith($fileUrl, '.pdf');
                                            @endphp
                                            @if($isPdf)
                                                <img src="{{ asset('frontend/img/pdf-icon.png') }}" alt="PDF"
                                                     class="img-fluid subject-image" style="max-height:80px; object-fit:cover;">
                                            @elseif($fileUrl)
                                                <img src="{{ $fileUrl }}" alt="Aperçu"
                                                     class="img-fluid subject-image" style="max-height:80px; object-fit:cover;">
                                            @else
                                                <div class="subject-image d-flex align-items-center justify-content-center bg-light" 
                                                     style="height:80px; width:80px; margin:0 auto;">
                                                    <i class="bi bi-file-earmark-text text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body py-3 px-3">
                                            <h6 class="subject-title mb-2">
                                                {{ Str::limit($sujet->libelle, 35) }}
                                                <span class="modern-badge badge-code">{{ $sujet->code }}</span>
                                            </h6>
                                            <p class="subject-description mb-3" style="min-height:40px;">
                                                {{ Str::limit($sujet->description, 70) }}
                                            </p>
                                            <div class="mb-3">
                                                <span class="modern-badge badge-matiere">{{ $sujet->matiere->libelle ?? 'Non définie' }}</span>
                                                @foreach ($sujet->niveaux as $niveau)
                                                    <span class="modern-badge badge-niveau">{{ $niveau->libelle }}</span>
                                                @endforeach
                                                <span class="modern-badge badge-annee">{{ $sujet->annee }}</span>
                                                <span class="modern-badge badge-categorie">{{ $sujet->categorie->libelle ?? 'Générale' }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <span class="publication-date">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $sujet->created_at->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('sujet.front.show', $sujet->libelle) }}"
                                                    class="btn btn-sm btn-detail">
                                                    <i class="bi bi-eye me-1"></i>Détails
                                                </a>
                                                @auth
                                                    @if (auth()->user()->points > 0)
                                                        @if ($sujet->getFirstMediaUrl('non_corrige'))
                                                            <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'non_corrige']) }}"
                                                                target="_blank" class="btn btn-download btn-sm">
                                                                <i class="bi bi-download me-1"></i>Sujet
                                                            </a>
                                                        @endif
                                                        @if ($sujet->getFirstMediaUrl('corrige'))
                                                            <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'corrige']) }}"
                                                                target="_blank" class="btn btn-success btn-sm">
                                                                <i class="bi bi-download me-1"></i>Corrigé
                                                            </a>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-outline-warning btn-sm" disabled>
                                                            <i class="bi bi-exclamation-triangle me-1"></i>Points insuffisants
                                                        </button>
                                                    @endif
                                                @else
                                                    <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary btn-sm">
                                                        <i class="bi bi-lock me-1"></i>Se connecter
                                                    </a>
                                                @endauth
                                            </div>
                                        </div>
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


