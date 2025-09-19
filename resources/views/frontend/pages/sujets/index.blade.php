<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\sujets\index.blade.php -->
@extends('frontend.layouts.front_app')

@section('content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
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
        <div class="row">
            <!-- Sidebar améliorée avec menu récursif -->
            <div class="col-lg-3 mb-4">
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
            <div class="col-lg-9">
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
                <!-- Liste des sujets -->
                <div class="row g-4">
                    @forelse($sujets as $sujet)
                        <div class="col-md-6 col-xl-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">{{ $sujet->libelle }}
                                        <span class="badge bg-dark">{{ $sujet->code }}</span>
                                    </h6>
                                    <p class="card-text">{{ $sujet->description }}</p>
                                    <div class="mb-2">
                                        <span class="badge bg-info">{{ $sujet->matiere->libelle ?? '' }}</span>
                                        @foreach ($sujet->niveaux as $niveau)
                                            <span class="badge bg-secondary">{{ $niveau->libelle }}</span>
                                        @endforeach
                                        <span class="badge bg-warning text-dark">{{ $sujet->annee }}</span>
                                        <span class="badge bg-success">{{ $sujet->categorie->libelle ?? '' }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-muted small">
                                            <i class="bi bi-calendar"></i>
                                            Publié le {{ $sujet->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('sujet.front.show', $sujet->libelle) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Voir les détails
                                        </a>
                                        @auth
                                            @if (auth()->user()->points > 0)
                                                <!-- Vérification des points -->
                                                @if ($sujet->getFirstMediaUrl('non_corrige'))
                                                    <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'non_corrige']) }}"
                                                        target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-download"></i> Télécharger le sujet
                                                    </a>
                                                @endif
                                                @if ($sujet->getFirstMediaUrl('corrige'))
                                                    <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'corrige']) }}"
                                                        target="_blank" class="btn btn-outline-success btn-sm">
                                                        <i class="bi bi-download"></i> Télécharger le corrigé
                                                    </a>
                                                @endif
                                            @else
                                                <a href="{{ route('user.sujet.create') }}"
                                                    class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-exclamation-triangle"></i> Points insuffisants pour
                                                    télécharger, créez un
                                                    sujet
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-lock"></i> Connectez-vous pour télécharger
                                            </a>
                                        @endauth
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
