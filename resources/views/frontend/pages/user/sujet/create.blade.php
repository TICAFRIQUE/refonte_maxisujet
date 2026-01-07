@extends('frontend.layouts.front_app')

@section('content')
    <!-- Header avec gradient -->
    <div class="container-fluid py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 200px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="text-white mb-2 fw-bold">
                        <i class="bi bi-file-earmark-plus me-2"></i>Publier un sujet
                    </h1>
                    <p class="text-white-50 mb-0">Partagez vos ressources pédagogiques avec la communauté</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-white bg-opacity-25 rounded-pill px-4 py-2 d-inline-block">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        <span class="text-white fw-bold">+10 points par sujet</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <!-- Breadcrumb moderne -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light rounded-pill shadow-sm px-4 py-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.dashboard') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-speedometer2 me-1"></i>Mon espace
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Créer un sujet</li>
            </ol>
        </nav>

        <!-- Guide d'aide -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="bi bi-lightbulb me-2"></i>Conseils pour une publication réussie
                                </h6>
                                <p class="text-muted mb-0 small">
                                    Vérifiez la qualité de vos fichiers • Ajoutez une description détaillée • 
                                    Sélectionnez les niveaux appropriés • Respectez les formats PDF/DOC
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <span class="badge bg-success rounded-pill">PDF</span>
                                    <span class="badge bg-success rounded-pill">DOC</span>
                                    <span class="badge bg-success rounded-pill">DOCX</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-white border-0 p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-1 fw-bold text-primary">
                                    <i class="bi bi-file-earmark-plus me-2"></i>Nouveau sujet
                                </h4>
                                <small class="text-muted">Remplissez tous les champs obligatoires pour publier votre sujet</small>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="formProgress"></div>
                                </div>
                                <small class="text-muted mt-1 d-block">Progression : <span id="progressText">0%</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                                <div class="d-flex align-items-center mb-3">
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

                        <form method="POST" action="{{ route('user.sujet.store') }}" enctype="multipart/form-data" 
                              class="needs-validation" novalidate id="sujetForm">
                            @csrf
                            
                            <!-- Section Informations générales -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-info-circle-fill text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Informations générales</h5>
                                        <small class="text-muted">Catégorie, matière et niveaux concernés</small>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="categorie_id" class="form-label fw-semibold">
                                            <i class="bi bi-folder me-1 text-primary"></i>Catégorie *
                                        </label>
                                        <select name="categorie_id" id="categorie_id" 
                                                class="form-select form-select-lg rounded-3 border-2 @error('categorie_id') is-invalid @enderror" 
                                                required onchange="updateProgress()">
                                            <option value="">Choisir une catégorie</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categorie_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="matiere_id" class="form-label fw-semibold">
                                            <i class="bi bi-book me-1 text-primary"></i>Matière *
                                        </label>
                                        <select name="matiere_id" id="matiere_id" 
                                                class="form-select form-select-lg rounded-3 border-2 @error('matiere_id') is-invalid @enderror" 
                                                required onchange="updateProgress()">
                                            <option value="">Choisir une matière</option>
                                            @foreach ($matieres as $mat)
                                                <option value="{{ $mat->id }}" {{ old('matiere_id') == $mat->id ? 'selected' : '' }}>
                                                    {{ $mat->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('matiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="niveaux" class="form-label fw-semibold">
                                            <i class="bi bi-diagram-3 me-1 text-primary"></i>Niveaux concernés *
                                        </label>
                                        <select name="niveaux[]" id="niveaux" 
                                                class="form-select form-select-lg rounded-3 border-2 @error('niveaux') is-invalid @enderror" 
                                                multiple required onchange="updateProgress()">
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
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>Maintenez Ctrl pour sélectionner plusieurs niveaux
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-5">

                            <!-- Section Contenu -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-file-text-fill text-info"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Description du sujet</h5>
                                        <small class="text-muted">Ajoutez une description détaillée pour aider les utilisateurs</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-semibold">
                                            <i class="bi bi-card-text me-1 text-info"></i>Description
                                        </label>
                                        <textarea name="description" id="description" 
                                                  class="form-control form-control-lg rounded-3 border-2 @error('description') is-invalid @enderror" 
                                                  rows="5" placeholder="Décrivez le contenu du sujet, les compétences évaluées, la durée de l'épreuve..." 
                                                  onkeyup="updateProgress()">{{ old('description') }}</textarea>
                                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-5">

                            <!-- Section Fichiers -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-cloud-upload-fill text-success"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Fichiers du sujet</h5>
                                        <small class="text-muted">Téléchargez le sujet et son corrigé (optionnel)</small>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label for="fichier_sujet" class="form-label fw-semibold">
                                            <i class="bi bi-file-earmark-pdf me-1 text-success"></i>Fichier du sujet *
                                        </label>
                                        <div class="upload-area border-2 border-dashed rounded-3 p-4 text-center position-relative" id="uploadArea1">
                                            <div class="upload-content">
                                                <i class="bi bi-cloud-upload text-muted" style="font-size: 2rem;"></i>
                                                <p class="mt-2 mb-1 fw-semibold text-muted">Glissez votre fichier ici</p>
                                                <p class="small text-muted">ou cliquez pour parcourir</p>
                                                <small class="text-success">PDF, DOC, DOCX • Max 10 MB</small>
                                            </div>
                                            <input type="file" name="non_corrige" id="fichier_sujet" 
                                                   class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 @error('non_corrige') is-invalid @enderror" 
                                                   accept=".pdf,.doc,.docx" required onchange="updateProgress(); handleFileSelect(this, 1)">
                                        </div>
                                        @error('fichier_sujet')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="fichier_corrige" class="form-label fw-semibold">
                                            <i class="bi bi-file-earmark-check me-1 text-success"></i>Corrigé (optionnel)
                                        </label>
                                        <div class="upload-area border-2 border-dashed rounded-3 p-4 text-center position-relative" id="uploadArea2">
                                            <div class="upload-content">
                                                <i class="bi bi-cloud-upload text-muted" style="font-size: 2rem;"></i>
                                                <p class="mt-2 mb-1 fw-semibold text-muted">Glissez votre corrigé ici</p>
                                                <p class="small text-muted">ou cliquez pour parcourir</p>
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

                            <hr class="my-5">

                            <!-- Section Métadonnées -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-calendar-event-fill text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">Informations complémentaires</h5>
                                        <small class="text-muted">Année scolaire du sujet</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="annee" class="form-label fw-semibold">
                                            <i class="bi bi-calendar3 me-1 text-warning"></i>Année
                                        </label>
                                        <select name="annee" id="annee" 
                                                class="form-select form-select-lg rounded-3 border-2 @error('annee') is-invalid @enderror">
                                            <option value="">Sélectionner une année</option>
                                            @for ($year = date('Y'); $year >= 1990; $year--)
                                                <option value="{{ $year }}" {{ old('annee') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('annee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="text-center pt-4 border-top">
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                                        <i class="bi bi-arrow-left me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold" id="submitBtn">
                                        <i class="bi bi-send me-2"></i>Publier le sujet
                                    </button>
                                </div>
                                <p class="text-muted mt-3 small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Votre sujet sera examiné par nos modérateurs avant publication
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
        border: 2px solid #dee2e6 !important;
        border-radius: 0.75rem !important;
        min-height: 48px !important;
    }
    .select2-container--default .select2-selection--single {
        border: 2px solid #dee2e6 !important;
        border-radius: 0.75rem !important;
        height: 48px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px !important;
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
    $('#annee').select2({
        placeholder: "Sélectionner une année",
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

    updateProgress();
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
            <small class="text-success">Fichier sélectionné avec succès</small>
        `;
        
        uploadArea.classList.add('file-selected');
    }
}

// Fonction pour mettre à jour la progression
function updateProgress() {
    let progress = 0;
    const totalFields = 4; // categorie, matiere, niveaux, fichier_sujet
    let filledFields = 0;
    
    // Vérifier chaque champ obligatoire
    if (document.getElementById('categorie_id').value) filledFields++;
    if (document.getElementById('matiere_id').value) filledFields++;
    if (document.getElementById('niveaux').value && document.getElementById('niveaux').value.length > 0) filledFields++;
    if (document.getElementById('fichier_sujet').files.length > 0) filledFields++;
    
    progress = (filledFields / totalFields) * 100;
    
    document.getElementById('formProgress').style.width = progress + '%';
    document.getElementById('progressText').textContent = Math.round(progress) + '%';
    
    // Changer la couleur de la barre de progression
    const progressBar = document.getElementById('formProgress');
    if (progress < 50) {
        progressBar.className = 'progress-bar bg-danger';
    } else if (progress < 100) {
        progressBar.className = 'progress-bar bg-warning';
    } else {
        progressBar.className = 'progress-bar bg-success';
    }
}
</script>
@endpush
