@extends('frontend.layouts.front_app')

@section('content')
    <div class="container">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            @include('frontend.components.retour')
        <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
            <ol class="breadcrumb bg-light rounded p-3 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.dashboard') }}" class="text-decoration-none">
                        <i class="bi bi-speedometer2 me-1"></i>Mon espace
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Créer un sujet</li>
            </ol>
        </nav>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 mb-3 gap-2">
            <div>
                <h1 class="fw-bold mb-1 fs-3">Publier un sujet</h1>
                <p class="text-muted mb-0 small">Partagez vos ressources pédagogiques avec la communauté</p>
            </div>
            <span class="points-pill">
                <i class="bi bi-star-fill"></i> +100 points par sujet approuvé
            </span>
        </div>

        <!-- Guide d'aide -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 px-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="fw-bold mb-1 small">
                                    <i class="bi bi-lightbulb me-2" style="color: var(--ms-blue);"></i>Conseils pour une publication réussie
                                </h6>
                                <p class="text-muted mb-0" style="font-size: 0.8rem;">
                                    Vérifiez la qualité de vos fichiers • Ajoutez une description détaillée •
                                    Sélectionnez les niveaux appropriés • Respectez les formats PDF/DOC
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <span class="badge" style="background: var(--ms-blue-light); color: var(--ms-blue-dark);">PDF</span>
                                    <span class="badge" style="background: var(--ms-blue-light); color: var(--ms-blue-dark);">DOC</span>
                                    <span class="badge" style="background: var(--ms-blue-light); color: var(--ms-blue-dark);">DOCX</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card">
                    <div class="card-header bg-white border-0 p-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1 fw-bold">
                                    <i class="bi bi-file-earmark-plus me-2" style="color: var(--ms-orange);"></i>Nouveau sujet
                                </h6>
                                <small class="text-muted">Remplissez tous les champs obligatoires pour publier votre sujet</small>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%; background: var(--ms-blue);" id="formProgress"></div>
                                </div>
                                <small class="text-muted mt-1 d-block">Progression : <span id="progressText">0%</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 shadow-sm py-2">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle p-2 me-3" style="background: var(--ms-danger-bg);">
                                        <i class="bi bi-exclamation-triangle-fill" style="color: var(--ms-danger);"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold" style="color: var(--ms-danger);">Erreurs de validation</h6>
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
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle p-2 me-2" style="background: var(--ms-blue-light);">
                                        <i class="bi bi-info-circle-fill" style="color: var(--ms-blue);"></i>
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
                                        <label for="matiere_id" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-book me-1 text-primary"></i>Matière *
                                        </label>
                                        <select name="matiere_id" id="matiere_id"
                                                class="form-select rounded-3 @error('matiere_id') is-invalid @enderror"
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
                                        <label for="niveaux" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-diagram-3 me-1 text-primary"></i>Niveaux concernés *
                                        </label>
                                        <select name="niveaux[]" id="niveaux"
                                                class="form-select rounded-3 @error('niveaux') is-invalid @enderror"
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
                                        <div class="form-text small">
                                            <i class="bi bi-info-circle me-1"></i>Maintenez Ctrl pour sélectionner plusieurs niveaux
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Section Contenu -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle p-2 me-2" style="background: var(--ms-blue-light);">
                                        <i class="bi bi-file-text-fill" style="color: var(--ms-blue);"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Description du sujet</h6>
                                        <small class="text-muted">Ajoutez une description détaillée pour aider les utilisateurs</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-card-text me-1"></i>Description
                                        </label>
                                        <textarea name="description" id="description"
                                                  class="form-control rounded-3 @error('description') is-invalid @enderror"
                                                  rows="3" placeholder="Décrivez le contenu du sujet, les compétences évaluées, la durée de l'épreuve..."
                                                  onkeyup="updateProgress()">{{ old('description') }}</textarea>
                                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Section Fichiers -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle p-2 me-2" style="background: var(--ms-orange-light);">
                                        <i class="bi bi-cloud-upload-fill" style="color: var(--ms-orange);"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Fichiers du sujet</h6>
                                        <small class="text-muted">Téléchargez le sujet et son corrigé (optionnel)</small>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label for="fichier_sujet" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>Fichier du sujet *
                                        </label>
                                        <div class="upload-area border-2 border-dashed rounded-3 p-2 text-center position-relative" id="uploadArea1">
                                            <div class="upload-content">
                                                <i class="bi bi-cloud-upload text-muted" style="font-size: 1.4rem;"></i>
                                                <p class="mt-1 mb-0 fw-semibold text-muted small">Glissez votre fichier ici</p>
                                                <p class="small text-muted mb-0">ou cliquez pour parcourir</p>
                                                <small class="text-muted">PDF, DOC, DOCX • Max 10 MB</small>
                                            </div>
                                            <input type="file" name="non_corrige" id="fichier_sujet"
                                                   class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 @error('non_corrige') is-invalid @enderror"
                                                   accept=".pdf,.doc,.docx" required onchange="updateProgress(); handleFileSelect(this, 1)">
                                        </div>
                                        @error('fichier_sujet')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="fichier_corrige" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-file-earmark-check me-1"></i>Corrigé (optionnel)
                                        </label>
                                        <div class="upload-area border-2 border-dashed rounded-3 p-2 text-center position-relative" id="uploadArea2">
                                            <div class="upload-content">
                                                <i class="bi bi-cloud-upload text-muted" style="font-size: 1.4rem;"></i>
                                                <p class="mt-1 mb-0 fw-semibold text-muted small">Glissez votre corrigé ici</p>
                                                <p class="small text-muted mb-0">ou cliquez pour parcourir</p>
                                                <small class="text-muted">PDF, DOC, DOCX • Max 10 MB</small>
                                            </div>
                                            <input type="file" name="corrige" id="fichier_corrige"
                                                   class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 @error('corrige') is-invalid @enderror"
                                                   accept=".pdf,.doc,.docx" onchange="handleFileSelect(this, 2)">
                                        </div>
                                        @error('fichier_corrige')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Section Métadonnées -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle p-2 me-2" style="background: var(--ms-blue-light);">
                                        <i class="bi bi-calendar-event-fill" style="color: var(--ms-blue);"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Informations complémentaires</h6>
                                        <small class="text-muted">Année scolaire du sujet</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="annee" class="form-label fw-semibold small mb-1">
                                            <i class="bi bi-calendar3 me-1"></i>Année
                                        </label>
                                        <select name="annee" id="annee"
                                                class="form-select rounded-3 @error('annee') is-invalid @enderror">
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
                            <div class="text-center pt-3 border-top">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-arrow-left me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-warning px-4 fw-bold" id="submitBtn">
                                        <i class="bi bi-send me-2"></i>Publier le sujet
                                    </button>
                                </div>
                                <p class="text-muted mt-2 small mb-0">
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
        border-color: var(--ms-blue) !important;
        background-color: var(--ms-blue-light);
    }
    .upload-area.drag-over {
        border-color: var(--ms-blue) !important;
        background-color: var(--ms-blue-light);
    }
    .file-selected {
        border-color: var(--ms-success) !important;
        background-color: var(--ms-success-bg);
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
