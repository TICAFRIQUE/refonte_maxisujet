<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\sujets\show.blade.php -->
@extends('frontend.layouts.front_app')
@section('title', $sujet->libelle . ' - ' . ($sujet->matiere->libelle ?? 'Sujet') . ' | MaxiSujets')
@section('meta_description', 'Téléchargez le sujet ' . $sujet->libelle . ' (' . ($sujet->matiere->libelle ?? '') . '). Document éducatif avec corrigé disponible.')
@section('meta_keywords', ($sujet->matiere->libelle ?? '') . ', sujet, exercice corrigé, téléchargement, ' . $sujet->libelle)
@section('og_title', $sujet->libelle . ' - ' . ($sujet->matiere->libelle ?? 'Sujet'))
@section('og_description', 'Téléchargez ce sujet' . ($sujet->matiere->libelle ? ' de ' . $sujet->matiere->libelle : '') . ' avec corrigé.')
@section('og_image', asset('frontend/img/logo.png'))

@section('content')

    @push('styles')
        <style>
            .detail-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); border: none; }

            .info-grid { background: var(--ms-bg-soft); border-radius: 8px; padding: 1rem; margin: 1rem 0; }
            .info-item { display: flex; align-items: center; margin-bottom: 0.5rem; }
            .info-item:last-child { margin-bottom: 0; }
            .info-item i { margin-right: 0.5rem; color: var(--ms-muted); width: 16px; }

            .simple-badge { background: #e2e8f0; color: #475569; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 500; margin-right: 0.5rem; }
            .simple-badge.primary { background: var(--ms-blue-light); color: var(--ms-blue-dark); }
            .simple-badge.success { background: #dcfce7; color: #16a34a; }
            .simple-badge.warning { background: var(--ms-orange-light); color: var(--ms-orange-dark); }
            .simple-badge.dark { background: var(--ms-navy); color: white; }

            .preview-section { background: var(--ms-bg-soft); border-radius: 12px; padding: 1.5rem; text-align: center; }
            .preview-container { position: relative; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom: 1rem; }
            .guest-preview-trigger { cursor: pointer; transition: box-shadow 0.2s ease; }
            .guest-preview-trigger:hover { box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15); }
            .pdf-preview { width: 100%; height: 300px; border: none; border-radius: 8px; }

            .file-info-badge { position: absolute; top: 10px; right: 10px; background: rgba(0, 0, 0, 0.8); color: white; padding: 0.3rem 0.6rem; border-radius: 15px; font-size: 0.75rem; z-index: 10; }

            .download-btn { background: var(--ms-orange); color: white; border: none; border-radius: 8px; padding: 0.8rem 1.5rem; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
            .download-btn:hover { background: var(--ms-orange-dark); color: white; transform: translateY(-2px); }
            .download-btn.success { background: #16a34a; }
            .download-btn.success:hover { background: #15803d; }

            .alert-simple { border: none; border-radius: 8px; padding: 1rem; }

            .points-summary { background: var(--ms-blue-light); border-radius: 12px; padding: 1.25rem; text-align: center; }

            .similar-card { border-radius: 12px; transition: transform 0.2s ease; height: 100%; }
            .similar-card:hover { transform: translateY(-4px); }
        </style>
    @endpush

    <div class="container">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            @include('frontend.components.retour')
        <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
            <ol class="breadcrumb bg-light rounded p-3">
                <li class="breadcrumb-item"><a href="{{ route('accueil') }}" class="text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sujet.front.index') }}" class="text-decoration-none">Sujets</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($sujet->libelle, 30) }}</li>
            </ol>
        </nav>
        </div>

        <div class="row">
            <!-- Informations du sujet -->
            <div class="col-lg-8 mb-4">
                <div class="detail-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <h2 class="text-dark mb-0">{{ $sujet->libelle }}</h2>
                            <span class="simple-badge dark">{{ $sujet->code }}</span>
                        </div>

                        @if ($sujet->description)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Description</h6>
                                <p class="text-dark">{{ $sujet->description }}</p>
                            </div>
                        @endif

                        <div class="info-grid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item"><i class="bi bi-book"></i><div><strong>Matière:</strong> {{ $sujet->matiere->libelle ?? 'Non définie' }}</div></div>
                                    <div class="info-item"><i class="bi bi-calendar"></i><div><strong>Année:</strong> {{ $sujet->annee }}</div></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item"><i class="bi bi-tag"></i><div><strong>Catégorie:</strong> {{ $sujet->categorie->libelle ?? 'Générale' }}</div></div>
                                    <div class="info-item"><i class="bi bi-clock"></i><div><strong>Publié le:</strong> {{ $sujet->created_at->format('d/m/Y') }}</div></div>
                                </div>
                            </div>

                            @if ($sujet->niveaux->count() > 0)
                                <div class="info-item mt-2">
                                    <i class="bi bi-mortarboard"></i>
                                    <div>
                                        <strong>Niveaux:</strong>
                                        @foreach ($sujet->niveaux as $niveau)
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
                @auth
                    <div class="points-summary mb-4">
                        <div class="points-pill mb-1">
                            <i class="bi bi-star-fill"></i> {{ auth()->user()->points }} point{{ auth()->user()->points > 1 ? 's' : '' }}
                        </div>
                        <div class="small text-muted">L'aperçu est gratuit. 1 point est déduit à chaque téléchargement.</div>
                        @if (auth()->user()->points <= 0)
                            <a href="{{ route('user.sujet.create') }}" class="btn btn-sm btn-warning mt-2">
                                <i class="bi bi-plus-circle me-1"></i>Publier un sujet pour gagner des points
                            </a>
                        @endif
                    </div>
                @endauth

                @php
                    $mediaNonCorrige = $sujet->getFirstMedia('non_corrige');
                    $mediaCorrige = $sujet->getFirstMedia('corrige');
                @endphp

                <!-- Sujet -->
                <div class="detail-card mb-4">
                    <div class="card-body p-4">
                        <h6 class="mb-3" style="color: var(--ms-blue);"><i class="bi bi-file-earmark-text me-2"></i>Sujet</h6>

                        <div class="preview-section mb-3">
                            @auth
                                @if ($mediaNonCorrige)
                                    @php
                                        $extension = strtolower($mediaNonCorrige->extension);
                                        $isPdf = $extension === 'pdf';
                                        $isDoc = in_array($extension, ['doc', 'docx']);
                                        $sizeMB = round($mediaNonCorrige->size / 1048576, 2);
                                        $apercuUrl = route('sujet.front.apercu', ['id' => $sujet->id, 'type' => 'non_corrige']);
                                    @endphp
                                    <div class="preview-container">
                                        @if ($isPdf)
                                            <iframe src="{{ $apercuUrl }}#toolbar=0&navpanes=0&scrollbar=0" class="pdf-preview" title="Aperçu du sujet"></iframe>
                                            <div class="file-info-badge"><i class="bi bi-filetype-pdf me-1"></i>PDF • {{ $sizeMB }}MB</div>
                                        @elseif ($isDoc)
                                            <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa;">
                                                <div class="text-center">
                                                    <i class="bi bi-file-earmark-word text-primary" style="font-size: 3rem;"></i>
                                                    <p class="mt-2 text-muted mb-0">{{ strtoupper($extension) }} • {{ $sizeMB }}MB</p>
                                                    <a href="{{ $apercuUrl }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Ouvrir l'aperçu</a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa;">
                                                <div class="text-center">
                                                    <i class="bi bi-file-earmark text-muted" style="font-size: 3rem;"></i>
                                                    <small class="text-muted">{{ strtoupper($extension) }} • {{ $sizeMB }}MB</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-simple alert-info mb-0"><i class="bi bi-info-circle me-2"></i>Aucun fichier disponible</div>
                                @endif
                            @else
                                <div class="preview-container guest-preview-trigger" data-bs-toggle="modal" data-bs-target="#loginRequiredModal" role="button" tabindex="0">
                                    <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa;">
                                        <div class="text-center">
                                            <i class="bi bi-eye" style="font-size: 2.5rem; color: var(--ms-blue);"></i>
                                            <p class="mt-2 mb-0 fw-semibold" style="color: var(--ms-blue);">Cliquez pour voir l'aperçu</p>
                                            <small class="text-muted">Gratuit — connexion requise</small>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>

                        @auth
                            @if ($mediaNonCorrige)
                                @if (auth()->user()->points > 0)
                                    <button type="button" class="download-btn w-100 text-center border-0"
                                        data-bs-toggle="modal" data-bs-target="#confirmDownloadModal"
                                        data-download-url="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'non_corrige']) }}"
                                        data-label="le sujet">
                                        <i class="bi bi-download me-2"></i>Télécharger le sujet (1 point)
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-exclamation-triangle me-2"></i>Points insuffisants
                                    </button>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-lock me-2"></i>Se connecter</a>
                        @endauth
                    </div>
                </div>

                <!-- Corrigé -->
                <div class="detail-card mb-4">
                    <div class="card-body p-4">
                        <h6 class="text-success mb-3"><i class="bi bi-file-earmark-check me-2"></i>Corrigé</h6>

                        <div class="preview-section mb-3">
                            @auth
                                @if ($mediaCorrige)
                                    @php
                                        $extensionC = strtolower($mediaCorrige->extension);
                                        $isPdfC = $extensionC === 'pdf';
                                        $isDocC = in_array($extensionC, ['doc', 'docx']);
                                        $sizeMBC = round($mediaCorrige->size / 1048576, 2);
                                        $apercuUrlC = route('sujet.front.apercu', ['id' => $sujet->id, 'type' => 'corrige']);
                                    @endphp
                                    <div class="preview-container">
                                        @if ($isPdfC)
                                            <iframe src="{{ $apercuUrlC }}#toolbar=0&navpanes=0&scrollbar=0" class="pdf-preview" title="Aperçu du corrigé"></iframe>
                                            <div class="file-info-badge"><i class="bi bi-filetype-pdf me-1"></i>PDF • {{ $sizeMBC }}MB</div>
                                        @elseif ($isDocC)
                                            <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa;">
                                                <div class="text-center">
                                                    <i class="bi bi-file-earmark-word text-primary" style="font-size: 3rem;"></i>
                                                    <p class="mt-2 text-muted mb-0">{{ strtoupper($extensionC) }} • {{ $sizeMBC }}MB</p>
                                                    <a href="{{ $apercuUrlC }}" target="_blank" class="btn btn-sm btn-outline-success mt-2">Ouvrir l'aperçu</a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa;">
                                                <div class="text-center">
                                                    <i class="bi bi-file-earmark-check text-success" style="font-size: 3rem;"></i>
                                                    <small class="text-muted">{{ strtoupper($extensionC) }} • {{ $sizeMBC }}MB</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-simple alert-info mb-0"><i class="bi bi-info-circle me-2"></i>Corrigé non disponible</div>
                                @endif
                            @else
                                <div class="preview-container guest-preview-trigger" data-bs-toggle="modal" data-bs-target="#loginRequiredModal" role="button" tabindex="0">
                                    <div class="d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa;">
                                        <div class="text-center">
                                            <i class="bi bi-eye" style="font-size: 2.5rem; color: #16a34a;"></i>
                                            <p class="mt-2 mb-0 fw-semibold" style="color: #16a34a;">Cliquez pour voir l'aperçu</p>
                                            <small class="text-muted">Gratuit — connexion requise</small>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>

                        @auth
                            @if ($mediaCorrige)
                                @if (auth()->user()->points > 0)
                                    <button type="button" class="download-btn success w-100 text-center border-0"
                                        data-bs-toggle="modal" data-bs-target="#confirmDownloadModal"
                                        data-download-url="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'corrige']) }}"
                                        data-label="le corrigé">
                                        <i class="bi bi-download me-2"></i>Télécharger le corrigé (1 point)
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-exclamation-triangle me-2"></i>Points insuffisants
                                    </button>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-lock me-2"></i>Se connecter</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Sujets similaires -->
        @if ($similaires->isNotEmpty())
            <div class="mt-5">
                <h4 class="mb-4"><i class="bi bi-collection me-2" style="color: var(--ms-blue);"></i>Sujets similaires</h4>
                <div class="row g-4">
                    @foreach ($similaires as $similaire)
                        <div class="col-md-6 col-xl-3">
                            <div class="card similar-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            @php
                                                $mediaSimilaire = $similaire->getFirstMedia('non_corrige');
                                                $extSimilaire = $mediaSimilaire ? strtolower($mediaSimilaire->extension) : null;
                                                $isPdfSimilaire = $extSimilaire === 'pdf';
                                                $isDocSimilaire = in_array($extSimilaire, ['doc', 'docx']);
                                            @endphp
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                                style="width:60px; height:60px; overflow:hidden; position:relative;">
                                                @auth
                                                    @if ($mediaSimilaire && $isPdfSimilaire)
                                                        <iframe src="{{ route('sujet.front.apercu', ['id' => $similaire->id, 'type' => 'non_corrige']) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                                            style="position:absolute; top:0; left:0; width: 260px; height: 260px; border: none; transform: scale(0.23); transform-origin: top left; pointer-events: none;"
                                                            tabindex="-1" title="Aperçu du sujet"></iframe>
                                                    @elseif ($isDocSimilaire)
                                                        <i class="bi bi-filetype-doc text-primary" style="font-size: 1.5rem;"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 1.5rem;"></i>
                                                    @endif
                                                @else
                                                    @if ($isPdfSimilaire)
                                                        <i class="bi bi-filetype-pdf text-danger" style="font-size: 1.5rem;"></i>
                                                    @elseif ($isDocSimilaire)
                                                        <i class="bi bi-filetype-doc text-primary" style="font-size: 1.5rem;"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 1.5rem;"></i>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-2">{{ Str::limit($similaire->libelle, 35) }}</h6>
                                            <div>
                                                <span class="simple-badge primary">{{ $similaire->matiere->libelle ?? '' }}</span>
                                                <span class="simple-badge warning">{{ $similaire->annee }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('sujet.front.show', $similaire->libelle) }}" class="btn btn-sm btn-outline-primary w-100">
                                        Voir ce sujet
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Cycles et niveaux -->
        <div class="mt-5">
            @include('frontend.components.old.cycle_niveaux')
        </div>
    </div>

    {{-- La modale "connexion requise" est partagée, définie une seule fois dans le layout (front_app.blade.php). --}}

    <!-- Modal de confirmation de téléchargement -->
    <div class="modal fade" id="confirmDownloadModal" tabindex="-1" aria-labelledby="confirmDownloadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDownloadModalLabel"><i class="bi bi-star-fill me-2 text-warning"></i>Confirmer le téléchargement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    @auth
                        <p class="mb-1">Télécharger <strong id="confirmDownloadLabel"></strong> coûte <strong>1 point</strong>.</p>
                        <p class="text-muted mb-0">
                            Solde actuel : <strong>{{ auth()->user()->points }}</strong> →
                            après téléchargement : <strong>{{ max(auth()->user()->points - 1, 0) }}</strong>
                        </p>
                    @endauth
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="#" id="confirmDownloadLink" class="btn btn-primary" style="background: var(--ms-orange); border-color: var(--ms-orange);">
                        <i class="bi bi-download me-1"></i>Confirmer
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const confirmModal = document.getElementById('confirmDownloadModal');
                if (confirmModal) {
                    confirmModal.addEventListener('show.bs.modal', function (event) {
                        const trigger = event.relatedTarget;
                        document.getElementById('confirmDownloadLink').setAttribute('href', trigger.getAttribute('data-download-url'));
                        document.getElementById('confirmDownloadLabel').textContent = trigger.getAttribute('data-label');
                    });
                }

                // Le téléchargement est un fichier (pas une navigation) : le navigateur reste
                // sur la page. On recharge juste après pour que le solde de points affiché
                // reflète le point qui vient d'être déduit côté serveur.
                const confirmLink = document.getElementById('confirmDownloadLink');
                if (confirmLink) {
                    confirmLink.addEventListener('click', function () {
                        setTimeout(() => window.location.reload(), 1200);
                    });
                }
            });
        </script>
    @endpush
@endsection
