<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\sujets\show.blade.php -->
@extends('frontend.layouts.front_app')
@section('title', 'Sujet de ' . $sujet->matiere->libelle . ' - ' . $sujet->titre . ' | MaxiSujets')
@section('meta_description', 'Téléchargez le sujet de ' . $sujet->matiere->libelle . ' : ' . $sujet->titre . '. Document éducatif gratuit  avec corrigé disponible.')
@section('meta_keywords', $sujet->matiere->libelle . ',  sujet gratuit, exercice corrigé, téléchargement, ' . $sujet->titre)
@section('og_title', $sujet->titre . ' - Sujet de ' . $sujet->matiere->libelle)
@section('og_description', 'Téléchargez gratuitement ce sujet de ' . $sujet->matiere->libelle . ' avec corrigé détaillé.')
@section('og_image', $sujet->getFirstMediaUrl('non_corrige') ?: asset('frontend/img/logo.png'))

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: none;
    }

    .info-grid {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem 0;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-item i {
        margin-right: 0.5rem;
        color: #64748b;
        width: 16px;
    }

    .simple-badge {
        background: #e2e8f0;
        color: #475569;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-right: 0.5rem;
    }

    .simple-badge.primary {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .simple-badge.success {
        background: #dcfce7;
        color: #16a34a;
    }

    .simple-badge.warning {
        background: #fef3c7;
        color: #d97706;
    }

    .simple-badge.dark {
        background: #1e293b;
        color: white;
    }

    .preview-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }

    .preview-container {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
    }

    .pdf-preview {
        width: 100%;
        height: 300px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pdf-preview:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .preview-image {
        max-width: 150px;
        max-height: 150px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .preview-image:hover {
        border-color: #ff6b35;
        transform: scale(1.05);
    }

    .preview-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 8px;
        cursor: pointer;
    }

    .preview-container:hover .preview-overlay {
        opacity: 1;
    }

    .preview-modal .modal-dialog {
        max-width: 90vw;
        width: 90vw;
        margin: 30px auto;
    }

    .preview-modal .modal-body {
        padding: 0;
        max-height: 80vh;
        overflow: auto;
    }

    .full-preview {
        width: 100%;
        height: 80vh;
        border: none;
    }

    .file-info-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 15px;
        font-size: 0.75rem;
        z-index: 10;
    }

    .download-btn {
        background: #ff6b35;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.8rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .download-btn:hover {
        background: #e55a2b;
        color: white;
        transform: translateY(-2px);
    }

    .download-btn.success {
        background: #16a34a;
    }

    .download-btn.success:hover {
        background: #15803d;
    }

    .alert-simple {
        border: none;
        border-radius: 8px;
        padding: 1rem;
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
            <li class="breadcrumb-item">
                <a href="{{ route('sujet.front.index') }}" class="text-decoration-none">
                    Sujets
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ Str::limit($sujet->libelle, 30) }}
            </li>
        </ol>
    </nav>

    <div class="row">
        <!-- Informations du sujet -->
        <div class="col-lg-8 mb-4">
            <div class="detail-card">
                <div class="card-body p-4">
                    <!-- En-tête -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h2 class="text-dark mb-0">{{ $sujet->libelle }}</h2>
                        <span class="simple-badge dark">{{ $sujet->code }}</span>
                    </div>

                    <!-- Description -->
                    @if($sujet->description)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p class="text-dark">{{ $sujet->description }}</p>
                        </div>
                    @endif

                    <!-- Informations organisées -->
                    <div class="info-grid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-book"></i>
                                    <div>
                                        <strong>Matière:</strong> {{ $sujet->matiere->libelle ?? 'Non définie' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-calendar"></i>
                                    <div>
                                        <strong>Année:</strong> {{ $sujet->annee }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="bi bi-tag"></i>
                                    <div>
                                        <strong>Catégorie:</strong> {{ $sujet->categorie->libelle ?? 'Générale' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-clock"></i>
                                    <div>
                                        <strong>Publié le:</strong> {{ $sujet->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($sujet->niveaux->count() > 0)
                            <div class="info-item mt-2">
                                <i class="bi bi-mortarboard"></i>
                                <div>
                                    <strong>Niveaux:</strong>
                                    @foreach($sujet->niveaux as $niveau)
                                        <span class="simple-badge">{{ $niveau->libelle }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Aperçus et téléchargements -->
        <div class="col-lg-4">
            <!-- Aperçu du sujet -->
            <div class="detail-card mb-4">
                <div class="card-body p-4">
                    <h6 class="text-primary mb-3">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Sujet
                    </h6>
                    <div class="preview-section mb-3">
                        @auth
                            @if(auth()->user()->points > 0 && $sujet->getFirstMediaUrl('non_corrige'))
                                @php
                                    $fileUrl = $sujet->getFirstMediaUrl('non_corrige');
                                    $fileName = $sujet->getFirstMedia('non_corrige')->name ?? 'Fichier';
                                    $fileSize = $sujet->getFirstMedia('non_corrige')->size ?? 0;
                                    $fileSizeMB = round($fileSize / 1048576, 2);
                                    $fileExtension = pathinfo($fileUrl, PATHINFO_EXTENSION);
                                    $isPdf = strtolower($fileExtension) === 'pdf';
                                    $isDoc = in_array(strtolower($fileExtension), ['doc', 'docx']);
                                @endphp
                                
                                <div class="preview-container" data-bs-toggle="modal" data-bs-target="#previewModal" 
                                     data-file-url="{{ $fileUrl }}" data-file-type="{{ $fileExtension }}"
                                     data-file-name="{{ $fileName }}">
                                    @if($isPdf)
                                        <iframe src="{{ $fileUrl }}#toolbar=0&navpanes=0&scrollbar=0" 
                                                class="pdf-preview" 
                                                title="Aperçu du sujet"></iframe>
                                        <div class="file-info-badge">
                                            <i class="bi bi-filetype-pdf me-1"></i>PDF • {{ $fileSizeMB }}MB
                                        </div>
                                    @elseif($isDoc)
                                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($fileUrl) }}" 
                                                class="pdf-preview" 
                                                title="Aperçu du document"></iframe>
                                        <div class="file-info-badge">
                                            <i class="bi bi-filetype-{{ strtolower($fileExtension) }} me-1"></i>{{ strtoupper($fileExtension) }} • {{ $fileSizeMB }}MB
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa; border-radius: 8px;">
                                            <div class="text-center">
                                                <i class="bi bi-file-earmark text-muted" style="font-size: 3rem;"></i>
                                                <p class="mt-2 text-muted">{{ $fileName }}</p>
                                                <small class="text-muted">{{ strtoupper($fileExtension) }} • {{ $fileSizeMB }}MB</small>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="preview-overlay">
                                        <i class="bi bi-eye" style="font-size: 2rem;"></i>
                                        <span class="mt-2">Cliquez pour agrandir</span>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-simple alert-info">
                                    @if(auth()->user()->points <= 0)
                                        <i class="bi bi-exclamation-circle me-2"></i>
                                        Points insuffisants pour voir l'aperçu
                                    @else
                                        <i class="bi bi-info-circle me-2"></i>
                                        Aperçu non disponible
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="alert alert-simple alert-warning">
                                <i class="bi bi-lock me-2"></i>
                                Connexion requise pour voir l'aperçu
                            </div>
                        @endauth
                    </div>
                    
                    @auth
                        @if(auth()->user()->points > 0 && $sujet->getFirstMediaUrl('non_corrige'))
                            <div class="d-grid gap-2">
                                <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'non_corrige']) }}" 
                                   class="download-btn w-100 text-center">
                                    <i class="bi bi-download me-2"></i>
                                    Télécharger le sujet
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-primary w-100"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#previewModal"
                                        data-file-url="{{ $fileUrl }}" 
                                        data-file-type="{{ $fileExtension }}"
                                        data-file-name="{{ $fileName }}">
                                    <i class="bi bi-eye me-2"></i>
                                    Aperçu avant télécharger
                                </button>
                            </div>
                        @else
                            <button class="btn btn-outline-secondary w-100" disabled>
                                <i class="bi bi-download me-2"></i>
                                {{ auth()->user()->points <= 0 ? 'Points insuffisants' : 'Non disponible' }}
                            </button>
                        @endif
                    @else
                        <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-lock me-2"></i>
                            Se connecter
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Aperçu du corrigé -->
            <div class="detail-card mb-4">
                <div class="card-body p-4">
                    <h6 class="text-success mb-3">
                        <i class="bi bi-file-earmark-check me-2"></i>
                        Corrigé
                    </h6>
                    <div class="preview-section mb-3">
                        @auth
                            @if(auth()->user()->points > 0 && $sujet->getFirstMediaUrl('corrige'))
                                @php
                                    $corrigeUrl = $sujet->getFirstMediaUrl('corrige');
                                    $corrigeName = $sujet->getFirstMedia('corrige')->name ?? 'Corrigé';
                                    $corrigeSize = $sujet->getFirstMedia('corrige')->size ?? 0;
                                    $corrigeSizeMB = round($corrigeSize / 1048576, 2);
                                    $corrigeExtension = pathinfo($corrigeUrl, PATHINFO_EXTENSION);
                                    $isPdfCorrige = strtolower($corrigeExtension) === 'pdf';
                                    $isDocCorrige = in_array(strtolower($corrigeExtension), ['doc', 'docx']);
                                @endphp
                                
                                <div class="preview-container" data-bs-toggle="modal" data-bs-target="#corrigePreviewModal" 
                                     data-file-url="{{ $corrigeUrl }}" data-file-type="{{ $corrigeExtension }}"
                                     data-file-name="{{ $corrigeName }}">
                                    @if($isPdfCorrige)
                                        <iframe src="{{ $corrigeUrl }}#toolbar=0&navpanes=0&scrollbar=0" 
                                                class="pdf-preview" 
                                                title="Aperçu du corrigé"></iframe>
                                        <div class="file-info-badge">
                                            <i class="bi bi-filetype-pdf me-1"></i>PDF • {{ $corrigeSizeMB }}MB
                                        </div>
                                    @elseif($isDocCorrige)
                                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($corrigeUrl) }}" 
                                                class="pdf-preview" 
                                                title="Aperçu du corrigé"></iframe>
                                        <div class="file-info-badge">
                                            <i class="bi bi-filetype-{{ strtolower($corrigeExtension) }} me-1"></i>{{ strtoupper($corrigeExtension) }} • {{ $corrigeSizeMB }}MB
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa; border-radius: 8px;">
                                            <div class="text-center">
                                                <i class="bi bi-file-earmark-check text-success" style="font-size: 3rem;"></i>
                                                <p class="mt-2 text-muted">{{ $corrigeName }}</p>
                                                <small class="text-muted">{{ strtoupper($corrigeExtension) }} • {{ $corrigeSizeMB }}MB</small>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="preview-overlay">
                                        <i class="bi bi-eye" style="font-size: 2rem;"></i>
                                        <span class="mt-2">Cliquez pour agrandir</span>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-simple alert-info">
                                    @if(auth()->user()->points <= 0)
                                        <i class="bi bi-exclamation-circle me-2"></i>
                                        Points insuffisants pour voir l'aperçu
                                    @else
                                        <i class="bi bi-info-circle me-2"></i>
                                        Corrigé non disponible
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="alert alert-simple alert-warning">
                                <i class="bi bi-lock me-2"></i>
                                Connexion requise pour voir l'aperçu
                            </div>
                        @endauth
                    </div>
                    
                    @auth
                        @if(auth()->user()->points > 0 && $sujet->getFirstMediaUrl('corrige'))
                            <div class="d-grid gap-2">
                                <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'corrige']) }}" 
                                   class="download-btn success w-100 text-center">
                                    <i class="bi bi-download me-2"></i>
                                    Télécharger le corrigé
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-success w-100"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#corrigePreviewModal"
                                        data-file-url="{{ $corrigeUrl }}" 
                                        data-file-type="{{ $corrigeExtension }}"
                                        data-file-name="{{ $corrigeName }}">
                                    <i class="bi bi-eye me-2"></i>
                                    Aperçu avant télécharger
                                </button>
                            </div>
                        @else
                            <button class="btn btn-outline-secondary w-100" disabled>
                                <i class="bi bi-download me-2"></i>
                                {{ auth()->user()->points <= 0 ? 'Points insuffisants' : 'Non disponible' }}
                            </button>
                        @endif
                    @else
                        <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-lock me-2"></i>
                            Se connecter
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Points utilisateur -->
            @auth
                <div class="detail-card">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-muted mb-2">Vos points</h6>
                        <div class="display-6 text-primary fw-bold">{{ auth()->user()->points }}</div>
                        @if(auth()->user()->points <= 0)
                            <div class="mt-3">
                                <a href="{{ route('user.sujet.create') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Gagner des points
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Cycles et niveaux -->
    <div class="mt-5">
        @include('frontend.components.cycle_niveaux')
    </div>
</div>

<!-- Modal d'aperçu pour le sujet -->
<div class="modal fade preview-modal" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Aperçu du sujet
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewModalBody">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <div class="me-auto">
                    <small class="text-muted" id="fileInfoText"></small>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Fermer
                </button>
                @auth
                    @if(auth()->user()->points > 0 && $sujet->getFirstMediaUrl('non_corrige'))
                        <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'non_corrige']) }}" 
                           class="btn btn-primary">
                            <i class="bi bi-download me-1"></i>Télécharger
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aperçu pour le corrigé -->
<div class="modal fade preview-modal" id="corrigePreviewModal" tabindex="-1" aria-labelledby="corrigePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="corrigePreviewModalLabel">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Aperçu du corrigé
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="corrigePreviewModalBody">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <div class="me-auto">
                    <small class="text-muted" id="corrigeFileInfoText"></small>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Fermer
                </button>
                @auth
                    @if(auth()->user()->points > 0 && $sujet->getFirstMediaUrl('corrige'))
                        <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'corrige']) }}" 
                           class="btn btn-success">
                            <i class="bi bi-download me-1"></i>Télécharger le corrigé
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des modals d'aperçu
    const previewModal = document.getElementById('previewModal');
    const corrigePreviewModal = document.getElementById('corrigePreviewModal');
    
    // Modal pour le sujet
    if (previewModal) {
        previewModal.addEventListener('show.bs.modal', function(event) {
            const trigger = event.relatedTarget;
            const fileUrl = trigger.getAttribute('data-file-url');
            const fileType = trigger.getAttribute('data-file-type');
            const fileName = trigger.getAttribute('data-file-name');
            
            const modalBody = document.getElementById('previewModalBody');
            const fileInfo = document.getElementById('fileInfoText');
            
            fileInfo.textContent = `${fileName} (${fileType.toUpperCase()})`;
            
            if (fileType.toLowerCase() === 'pdf') {
                modalBody.innerHTML = `<iframe src="${fileUrl}#toolbar=1&navpanes=1&scrollbar=1" class="full-preview" title="Aperçu complet"></iframe>`;
            } else if (['doc', 'docx'].includes(fileType.toLowerCase())) {
                modalBody.innerHTML = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(fileUrl)}" class="full-preview" title="Aperçu complet"></iframe>`;
            } else {
                modalBody.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Aperçu non disponible</h4>
                        <p class="text-muted">Ce type de fichier ne peut pas être prévisualisé dans le navigateur.</p>
                        <a href="${fileUrl}" target="_blank" class="btn btn-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Ouvrir dans un nouvel onglet
                        </a>
                    </div>
                `;
            }
        });
    }
    
    // Modal pour le corrigé
    if (corrigePreviewModal) {
        corrigePreviewModal.addEventListener('show.bs.modal', function(event) {
            const trigger = event.relatedTarget;
            const fileUrl = trigger.getAttribute('data-file-url');
            const fileType = trigger.getAttribute('data-file-type');
            const fileName = trigger.getAttribute('data-file-name');
            
            const modalBody = document.getElementById('corrigePreviewModalBody');
            const fileInfo = document.getElementById('corrigeFileInfoText');
            
            fileInfo.textContent = `${fileName} (${fileType.toUpperCase()})`;
            
            if (fileType.toLowerCase() === 'pdf') {
                modalBody.innerHTML = `<iframe src="${fileUrl}#toolbar=1&navpanes=1&scrollbar=1" class="full-preview" title="Aperçu complet du corrigé"></iframe>`;
            } else if (['doc', 'docx'].includes(fileType.toLowerCase())) {
                modalBody.innerHTML = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(fileUrl)}" class="full-preview" title="Aperçu complet du corrigé"></iframe>`;
            } else {
                modalBody.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-check text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Aperçu non disponible</h4>
                        <p class="text-muted">Ce type de fichier ne peut pas être prévisualisé dans le navigateur.</p>
                        <a href="${fileUrl}" target="_blank" class="btn btn-success">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Ouvrir dans un nouvel onglet
                        </a>
                    </div>
                `;
            }
        });
    }
});
</script>

@endsection