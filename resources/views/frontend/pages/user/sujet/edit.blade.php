@extends('frontend.layouts.front_app')

@section('content')
    <!-- Header avec gradient -->
    <div class="container-fluid py-2" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="text-white mb-0 fw-bold fs-4">
                        <i class="bi bi-pencil-square me-2"></i>Modifier le sujet
                    </h1>
                    <p class="text-white-50 mb-0 small">Apportez des modifications à votre sujet publié</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-white bg-opacity-25 rounded-pill px-3 py-1 d-inline-block">
                        <i class="bi bi-file-earmark-text text-white me-1"></i>
                        <span class="text-white fw-bold small">Modification</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-4">
        <!-- Breadcrumb moderne -->
        <div class="d-flex align-items-center gap-3 mb-3 mt-3 flex-wrap">
            @include('frontend.components.retour')
        <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
            <ol class="breadcrumb bg-light rounded-pill shadow-sm px-3 py-2 mb-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.dashboard') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-speedometer2 me-1"></i>Mon espace
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('user.sujet.index') }}" class="text-primary text-decoration-none">Mes sujets</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Modifier</li>
            </ol>
        </nav>
        </div>

        <!-- Information du sujet -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                    <div class="card-body p-2 px-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-25 rounded-circle p-2 me-2">
                                <i class="bi bi-info-circle-fill text-info"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold text-info small">Modification du sujet</h6>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;">
                                    Code: <strong>{{ $sujet->code ?? 'N/A' }}</strong> •
                                    Créé le: <strong>{{ $sujet->created_at->format('d/m/Y à H:i') }}</strong>
                                </p>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    Après modification, votre sujet sera de nouveau soumis à validation
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-white border-0 p-3">
                        <h6 class="mb-1 fw-bold text-info">
                            <i class="bi bi-pencil-square me-2"></i>Modifier le sujet
                        </h6>
                        <small class="text-muted">Modifiez les informations de votre sujet</small>
                    </div>
                    <div class="card-body p-3">
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 shadow-sm py-2">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-danger">Erreurs de validation</h6>
                                        <small class="text-muted">Veuillez corriger les erreurs ci-dessous</small>
                                    </div>
                                </div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li class="small">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('user.sujet.update', $sujet->id) }}" 
                              enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            
                            <!-- Section Informations générales -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-info-circle-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Informations générales</h6>
                                        <small class="text-muted">Catégorie, matière et niveaux concernés</small>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label for="categorie_id" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-folder me-1 text-primary"></i>Catégorie *
                                        </label>
                                        <select name="categorie_id" id="categorie_id"
                                                class="form-select rounded-3 @error('categorie_id') is-invalid @enderror"
                                                required>
                                            <option value="">Choisir une catégorie</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('categorie_id', $sujet->categorie_id) == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="matiere_id" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-book me-1 text-primary"></i>Matière *
                                        </label>
                                        <select name="matiere_id" id="matiere_id"
                                                class="form-select rounded-3 @error('matiere_id') is-invalid @enderror"
                                                required>
                                            <option value="">Choisir une matière</option>
                                            @foreach($matieres as $mat)
                                                <option value="{{ $mat->id }}" {{ old('matiere_id', $sujet->matiere_id) == $mat->id ? 'selected' : '' }}>
                                                    {{ $mat->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('matiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="niveaux" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-diagram-3 me-1 text-primary"></i>Niveaux concernés *
                                        </label>
                                        <select name="niveaux[]" id="niveaux"
                                                class="form-select rounded-3 @error('niveaux') is-invalid @enderror"
                                                multiple required>
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
                                        <div class="form-text small">
                                            <i class="bi bi-info-circle me-1"></i>Maintenez Ctrl pour sélectionner plusieurs niveaux
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Section Description -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-file-text-fill text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Description du sujet</h6>
                                        <small class="text-muted">Modifiez la description de votre sujet</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-card-text me-1 text-info"></i>Description
                                        </label>
                                        <textarea name="description" id="description"
                                                  class="form-control rounded-3 @error('description') is-invalid @enderror"
                                                  rows="3" placeholder="Décrivez le contenu du sujet, les compétences évaluées, la durée de l'épreuve...">{{ old('description', $sujet->description) }}</textarea>
                                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Section Fichiers -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-cloud-upload-fill text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Fichiers du sujet</h6>
                                        <small class="text-muted">Remplacez les fichiers si nécessaire (optionnel)</small>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label for="fichier_sujet" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-file-earmark-pdf me-1 text-success"></i>Fichier du sujet
                                        </label>

                                        @if($sujet->getFirstMedia('non_corrige'))
                                            <div class="card border-success border-2 mb-2">
                                                <div class="card-body p-2 bg-success bg-opacity-5">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-file-earmark-check text-success me-2"></i>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0 text-success small">Fichier actuel</h6>
                                                            <small class="text-muted" style="font-size: 0.75rem;">Cliquez pour consulter le fichier existant</small>
                                                        </div>
                                                        <a href="{{ route('sujet.front.apercu', ['id' => $sujet->id, 'type' => 'non_corrige']) }}" target="_blank"
                                                           class="btn btn-outline-success btn-sm">
                                                            <i class="bi bi-eye me-1"></i>Voir
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="upload-area border-2 border-dashed rounded-3 p-2 text-center position-relative" id="uploadArea1">
                                            <div class="upload-content">
                                                <i class="bi bi-cloud-upload text-muted" style="font-size: 1.4rem;"></i>
                                                <p class="mt-1 mb-0 fw-semibold text-muted small">Remplacer le fichier</p>
                                                <p class="small text-muted mb-0">ou cliquez pour parcourir</p>
                                                <small class="text-success">PDF, DOC, DOCX • Max 10 MB</small>
                                            </div>
                                            <input type="file" name="non_corrige" id="fichier_sujet"
                                                   class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 @error('non_corrige') is-invalid @enderror"
                                                   accept=".pdf,.doc,.docx" onchange="handleFileSelect(this, 1)">
                                        </div>
                                        @error('fichier_sujet')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="fichier_corrige" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-file-earmark-check me-1 text-success"></i>Corrigé (optionnel)
                                        </label>

                                        @if($sujet->getFirstMedia('corrige'))
                                            <div class="card border-success border-2 mb-2">
                                                <div class="card-body p-2 bg-success bg-opacity-5">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-file-earmark-check text-success me-2"></i>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0 text-success small">Corrigé actuel</h6>
                                                            <small class="text-muted" style="font-size: 0.75rem;">Cliquez pour consulter le corrigé existant</small>
                                                        </div>
                                                        <a href="{{ route('sujet.front.apercu', ['id' => $sujet->id, 'type' => 'corrige']) }}" target="_blank"
                                                           class="btn btn-outline-success btn-sm">
                                                            <i class="bi bi-eye me-1"></i>Voir
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="upload-area border-2 border-dashed rounded-3 p-2 text-center position-relative" id="uploadArea2">
                                            <div class="upload-content">
                                                <i class="bi bi-cloud-upload text-muted" style="font-size: 1.4rem;"></i>
                                                <p class="mt-1 mb-0 fw-semibold text-muted small">{{ $sujet->getFirstMedia('corrige') ? 'Remplacer' : 'Ajouter' }} le corrigé</p>
                                                <p class="small text-muted mb-0">ou cliquez pour parcourir</p>
                                                <small class="text-success">PDF, DOC, DOCX • Max 10 MB</small>
                                            </div>
                                            <input type="file" name="corrige" id="fichier_corrige"
                                                   class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 @error('corrige') is-invalid @enderror"
                                                   accept=".pdf,.doc,.docx" onchange="handleFileSelect(this, 2)">
                                        </div>
                                        @error('fichier_corrige')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="text-center pt-3 border-top">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('user.sujet.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="bi bi-arrow-left me-2"></i>Retour
                                    </a>
                                    <button type="submit" class="btn btn-info rounded-pill px-4 fw-bold">
                                        <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                                <p class="text-muted mt-2 small mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Votre sujet modifié sera de nouveau soumis à validation
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .upload-area {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .upload-area:hover {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05);
    }
    .upload-area.drag-over {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.1);
        transform: scale(1.02);
    }
    .file-selected {
        border-color: #198754 !important;
        background-color: rgba(25, 135, 84, 0.05);
    }
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #dee2e6 !important;
        border-radius: 0.5rem !important;
        min-height: 38px !important;
    }
    .select2-container--default .select2-selection--single {
        border: 1px solid #dee2e6 !important;
        border-radius: 0.5rem !important;
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialiser Select2
    $('#niveaux').select2({
        placeholder: "Sélectionner les niveaux concernés",
        allowClear: true,
        width: '100%'
    });
    $('#categorie_id').select2({
        placeholder: "Choisir une catégorie",
        allowClear: true,
        width: '100%'
    });
    $('#matiere_id').select2({
        placeholder: "Choisir une matière",
        allowClear: true,
        width: '100%'
    });

    // Gestion du drag & drop
    $('.upload-area').each(function() {
        const uploadArea = this;
        const fileInput = uploadArea.querySelector('input[type="file"]');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            uploadArea.classList.add('drag-over');
        }
        
        function unhighlight(e) {
            uploadArea.classList.remove('drag-over');
        }
        
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFileSelect(fileInput, uploadArea.id.slice(-1));
        }
    });
});

// Fonction pour gérer la sélection de fichier
function handleFileSelect(input, areaId) {
    const file = input.files[0];
    const uploadArea = document.getElementById('uploadArea' + areaId);
    const uploadContent = uploadArea.querySelector('.upload-content');
    
    if (file) {
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        uploadContent.innerHTML = `
            <i class="bi bi-file-earmark-check text-success" style="font-size: 2rem;"></i>
            <p class="mt-2 mb-1 fw-semibold text-success">${fileName}</p>
            <p class="small text-muted">${fileSize} MB</p>
            <small class="text-success">Nouveau fichier sélectionné</small>
        `;
        
        uploadArea.classList.add('file-selected');
    }
}
</script>
@endpush
