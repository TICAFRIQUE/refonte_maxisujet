@extends('frontend.layouts.front_app')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded shadow-sm p-4">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-primary text-decoration-none"><i class="bi bi-person-circle"></i> Mon espace</a></li>
            <li class="breadcrumb-item active" aria-current="page">Éditer le sujet</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Modifier le sujet</h5>
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
                    <form method="POST" action="{{ route('user.sujet.update', $sujet->id) }}" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label for="categorie_id" class="form-label">Catégorie</label>
                            <select name="categorie_id" id="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror" required>
                                <option value="">Sélectionner...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('categorie_id', $sujet->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->libelle }}</option>
                                @endforeach
                            </select>
                            @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="matiere_id" class="form-label">Matière</label>
                            <select name="matiere_id" id="matiere_id" class="form-select @error('matiere_id') is-invalid @enderror" required>
                                <option value="">Sélectionner...</option>
                                @foreach($matieres as $mat)
                                    <option value="{{ $mat->id }}" {{ old('matiere_id', $sujet->matiere_id) == $mat->id ? 'selected' : '' }}>{{ $mat->libelle }}</option>
                                @endforeach
                            </select>
                            @error('matiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label for="niveaux" class="form-label">Niveaux</label>
                            <select name="niveaux[]" id="niveaux" class="form-select @error('niveaux') is-invalid @enderror" multiple required>
                                @foreach($data_niveaux as $cycle)
                                    <optgroup label="{{ $cycle->libelle }}">
                                        @foreach ($cycle->children as $niveau)
                                            <option value="{{ $niveau->id }}" {{ (collect(old('niveaux', $sujet->niveaux->pluck('id')))->contains($niveau->id)) ? 'selected' : '' }}>
                                                {{ $niveau->libelle }}
                                            </option>
                                            @if($niveau->children && $niveau->children->count())
                                                @foreach($niveau->children as $subNiveau)
                                                    <option value="{{ $subNiveau->id }}" {{ (collect(old('niveaux', $sujet->niveaux->pluck('id')))->contains($subNiveau->id)) ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;{{ $subNiveau->libelle }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('niveaux')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                          <div class="col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $sujet->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fichier_sujet" class="form-label">Fichier du sujet (PDF, DOC...)</label>
                            <input type="file" name="non_corrige" id="fichier_sujet" class="form-control @error('non_corrige') is-invalid @enderror">
                            @if($sujet->getFirstMediaUrl('non_corrige'))
                                <a href="{{ $sujet->getFirstMediaUrl('non_corrige') }}" target="_blank" class="d-block mt-1">Voir le fichier actuel</a>
                            @endif
                            @error('fichier_sujet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fichier_corrige" class="form-label">Corrigé (optionnel)</label>
                            <input type="file" name="corrige" id="fichier_corrige" class="form-control @error('corrige') is-invalid @enderror">
                            @if($sujet->getFirstMediaUrl('corrige'))
                                <a href="{{ $sujet->getFirstMediaUrl('corrige') }}" target="_blank" class="d-block mt-1">Voir le corrigé actuel</a>
                            @endif
                            @error('fichier_corrige')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-info">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#niveaux').select2({
            placeholder: "Sélectionner les niveaux",
            allowClear: true
        });
    });
</script>
@endpush
