@extends('frontend.layouts.front_app')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-primary text-decoration-none"><i class="bi bi-person-circle"></i> Mon espace</a></li>
            <li class="breadcrumb-item active" aria-current="page">Créer un sujet</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nouveau sujet </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('user.sujet.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="categorie_id" class="form-label">Catégorie</label>
                                    <select name="categorie_id" id="categorie_id" class="form-control @error('categorie_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->libelle }}</option>
                                        @endforeach
                                    </select>
                                    @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="niveaux" class="form-label">Niveaux</label>
                                    <select name="niveaux[]" id="niveaux" class="form-control @error('niveaux') is-invalid @enderror" multiple required>
                                        @foreach ($data_niveaux as $cycle)
                                            <optgroup label="{{ $cycle->libelle }}">
                                                @foreach ($cycle->children as $niveau)
                                                    <option value="{{ $niveau->id }}" {{ (collect(old('niveaux'))->contains($niveau->id)) ? 'selected' : '' }}>
                                                        {{ $niveau->libelle }}
                                                    </option>
                                                    @if($niveau->children && $niveau->children->count())
                                                        @foreach($niveau->children as $subNiveau)
                                                            <option value="{{ $subNiveau->id }}" {{ (collect(old('niveaux'))->contains($subNiveau->id)) ? 'selected' : '' }}>
                                                                &nbsp;&nbsp;{{ $subNiveau->libelle }}
                                                            </option>
                                                            @if($subNiveau->children && $subNiveau->children->count())
                                                                @foreach($subNiveau->children as $subSubNiveau)
                                                                    <option value="{{ $subSubNiveau->id }}" {{ (collect(old('niveaux'))->contains($subSubNiveau->id)) ? 'selected' : '' }}>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $subSubNiveau->libelle }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('niveaux')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="matiere_id" class="form-label">Matière</label>
                                    <select name="matiere_id" id="matiere_id" class="form-control @error('matiere_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner</option>
                                        @foreach ($matieres as $mat)
                                            <option value="{{ $mat->id }}" {{ old('matiere_id') == $mat->id ? 'selected' : '' }}>{{ $mat->libelle }}</option>
                                        @endforeach
                                    </select>
                                    @error('matiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fichier_sujet" class="form-label">Fichier du sujet (PDF, DOC...)</label>
                                    <input type="file" name="non_corrige" id="fichier_sujet" class="form-control @error('non_corrige') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                                    @error('fichier_sujet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label for="fichier_corrige" class="form-label">Corrigé (optionnel)</label>
                                    <input type="file" name="corrige" id="fichier_corrige" class="form-control @error('corrige') is-invalid @enderror" accept=".pdf,.doc,.docx">
                                    @error('fichier_corrige')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label for="annee" class="form-label">Année</label>
                                    <select name="annee" id="annee" class="form-control @error('annee') is-invalid @enderror">
                                        <option value="" selected> Sélectionner</option>
                                        @for ($year = date('Y'); $year >= 1990; $year--)
                                            <option value="{{ $year }}" {{ old('annee') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                    @error('annee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Publier le sujet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Ajoute Data Choices en plus de Select2 -->
@push('scripts')
<!-- Inclure les fichiers CSS et JS de Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    // Initialiser Select2
    $(document).ready(function() {
        $('#niveaux').select2({
            placeholder: "Sélectionner les niveaux",
            allowClear: true
        });
        $('#categorie_id').select2({
            placeholder: "Sélectionner une catégorie",
            allowClear: true
        });
        $('#matiere_id').select2({
            placeholder: "Sélectionner une matière",
            allowClear: true
        });
        $('#annee').select2({
            placeholder: "Sélectionner une année",
            allowClear: true
        });
    });
</script>
@endpush
