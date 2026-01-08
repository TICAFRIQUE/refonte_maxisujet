{{-- <section class="container my-5">
    <h2 class="section-title">📚 Derniers documents</h2>
    <div class="row g-4">
        @foreach ($sujetsRecents as $sujet)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="row g-0 align-items-center">
                        <div class="col-4 text-center">
                            @php
                                $preview = $sujet->getFirstMediaUrl('non_corrige');
                                $isPdf = $preview && Str::endsWith($preview, '.pdf');
                            @endphp
                            <div class="p-2">
                                @if ($isPdf)
                                    <img src="{{ asset('frontend/img/pdf-icon.png') }}" alt="PDF"
                                         class="img-fluid rounded" style="max-height:90px; object-fit:cover; border:1px solid #eee;">
                                @elseif($preview)
                                    <img src="{{ $preview }}" alt="Aperçu"
                                         class="img-fluid rounded" style="max-height:90px; object-fit:cover; border:1px solid #eee;">
                                @else
                                    <img src="{{ asset('frontend/img/file-placeholder.png') }}" alt="Aperçu"
                                         class="img-fluid rounded" style="max-height:90px; object-fit:cover; border:1px solid #eee;">
                                @endif
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card-body py-3 px-2">
                                <h6 class="card-title text-primary mb-1 d-flex align-items-center">
                                    {{ $sujet->libelle }}
                                    <span class="badge bg-dark ms-2">{{ $sujet->code }}</span>
                                </h6>
                                <p class="card-text small mb-2" style="min-height:38px;">{{ Str::limit($sujet->description, 60) }}</p>
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
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('sujet.front.show', $sujet->libelle) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Détails
                                    </a>
                                    @auth
                                        @if (auth()->user()->points > 0)
                                            @if ($sujet->getFirstMediaUrl('non_corrige'))
                                                <a href="{{ $sujet->getFirstMediaUrl('non_corrige') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                                    <i class="bi bi-download"></i> Sujet
                                                </a>
                                            @endif
                                            @if ($sujet->getFirstMediaUrl('corrige'))
                                                <a href="{{ $sujet->getFirstMediaUrl('corrige') }}" class="btn btn-outline-success btn-sm" target="_blank">
                                                    <i class="bi bi-download"></i> Corrigé
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('user.sujet.create') }}" class="btn btn-outline-danger btn-sm">
                                               <small> <i class="bi bi-exclamation-triangle"></i> Points insuffisants pour télécharger</small>
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
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('sujet.front.index') }}" class="btn btn-primary px-4">
            <i class="bi bi-list"></i> Voir tous les sujets
        </a>
    </div>
</section> --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Derniers Documents</h2>
            <p class="lead text-muted">Découvrez les derniers ajouts à notre bibliothèque</p>
        </div>
        <div class="row g-4">
            @foreach ($sujetsRecents as $sujet)
                <div class="col-lg-4 col-md-6">
                    <div class="document-card h-100">
                        <!-- Aperçu du Document -->
                        <div class="document-preview position-relative">
                            @php
                                $preview = $sujet->getFirstMediaUrl('non_corrige');
                                $hasInsufficientPoints = auth()->check() && auth()->user()->points <= 0;
                                
                                if ($preview) {
                                    $fileExtension = pathinfo($preview, PATHINFO_EXTENSION);
                                    $isPdf = strtolower($fileExtension) === 'pdf';
                                    $isDoc = in_array(strtolower($fileExtension), ['doc', 'docx']);
                                }
                            @endphp
                            
                            <div class="{{ $hasInsufficientPoints ? 'opacity-50 position-relative' : '' }}" style="height: 150px; overflow: hidden; border-radius: 8px;">
                                @if ($preview)
                                    @if($isPdf)
                                        <iframe src="{{ $preview }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH" 
                                style="width: 120%; height: 200px; border: none; transform: scale(0.9); transform-origin: top left; pointer-events: none;"
                                title="Aperçu du sujet"></iframe>
                    @elseif($isDoc)
                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($preview) }}" 
                                style="width: 120%; height: 200px; border: none; transform: scale(0.9); transform-origin: top left; pointer-events: none;"
                                    @endif
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                        <i class="bi bi-file-earmark-text display-4 text-primary"></i>
                                    </div>
                                @endif
                                
                                @if ($hasInsufficientPoints)
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="text-center bg-white bg-opacity-90 p-2 rounded shadow">
                                            <i class="bi bi-lock text-warning fs-3"></i>
                                            <div class="small text-muted">Points insuffisants</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Contenu de la Carte -->
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold text-dark mb-0">{{ Str::limit($sujet->libelle, 50) }}</h5>
                                <span class="badge bg-dark">{{ $sujet->code }}</span>
                            </div>

                            <p class="text-muted mb-3" style="min-height: 48px;">
                                {{ Str::limit($sujet->description, 80) }}</p>

                            <!-- Badges d'Information -->
                            <div class="mb-3">
                                <span class="category-badge me-2">{{ $sujet->categorie->libelle ?? 'Document' }}</span>
                                <span class="badge subject-badge me-2">{{ $sujet->matiere->libelle ?? '' }}</span>
                                @foreach ($sujet->niveaux->take(2) as $niveau)
                                    <span class="badge level-badge me-1">{{ $niveau->libelle }}</span>
                                @endforeach
                            </div>

                            <!-- Date et Année -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $sujet->created_at->format('d/m/Y') }}
                                </small>
                                <span class="badge bg-warning text-dark">{{ $sujet->annee }}</span>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('sujet.front.show', $sujet->libelle) }}"
                                    class="btn btn-outline-primary rounded-pill flex-fill">
                                    <i class="bi bi-eye me-1"></i>Voir
                                </a>

                                <!-- Se connecter et avoir des points pour Téléchargement -->
                                {{-- @auth
                                    @if (auth()->user()->points > 0)
                                        @if ($sujet->getFirstMediaUrl('non_corrige'))
                                            <a href="{{ $sujet->getFirstMediaUrl('non_corrige') }}"
                                                class="btn btn-primary rounded-pill" target="_blank" 
                                                title="Télécharger le sujet">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @endif
                                        @if ($sujet->getFirstMediaUrl('corrige'))
                                            <a href="{{ $sujet->getFirstMediaUrl('corrige') }}"
                                                class="btn btn-success rounded-pill" target="_blank"
                                                title="Télécharger le corrigé">
                                                <i class="bi bi-file-check"></i>
                                            </a>
                                        @endif
                                    @else
                                        <button class="btn btn-outline-danger rounded-pill" disabled
                                                title="Points insuffisants pour télécharger">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('user.loginForm') }}" 
                                       class="btn btn-outline-secondary rounded-pill"
                                       title="Connectez-vous pour télécharger">
                                        <i class="bi bi-person"></i>
                                    </a>
                                @endauth --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('sujet.front.index') }}" class="modern-btn">
                <i class="bi bi-arrow-right me-2"></i>Voir Tous les Documents
            </a>
        </div>
    </div>
</section>
