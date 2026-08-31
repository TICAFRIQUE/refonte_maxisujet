<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Derniers Documents</h2>
            <p class="lead text-muted">Découvrez les derniers ajouts à notre bibliothèque</p>
        </div>
        <div class="row g-4">
            @forelse ($sujetsRecents as $sujet)
                <div class="col-lg-3 col-md-6">
                    <div class="document-card h-100">
                        <!-- Aperçu du document (gratuit, réservé aux connectés) -->
                        <div class="document-preview position-relative">
                            @php
                                $media = $sujet->getFirstMedia('non_corrige');
                                $extension = $media ? strtolower($media->extension) : null;
                                $isPdf = $extension === 'pdf';
                                $isDoc = in_array($extension, ['doc', 'docx']);
                            @endphp

                            <div style="height: 150px; overflow: hidden; border-radius: 8px;">
                                @auth
                                    @if ($media && $isPdf)
                                        <iframe src="{{ route('sujet.front.apercu', ['id' => $sujet->id, 'type' => 'non_corrige']) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                            style="width: 120%; height: 200px; border: none; transform: scale(0.9); transform-origin: top left; pointer-events: none;"
                                            title="Aperçu du sujet"></iframe>
                                    @elseif ($media)
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                            <i class="bi bi-file-earmark-{{ $isDoc ? 'word' : 'text' }} display-4 text-primary"></i>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                            <i class="bi bi-file-earmark-text display-4 text-muted"></i>
                                        </div>
                                    @endif
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light guest-preview-trigger"
                                        style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#loginRequiredModal"
                                        role="button" tabindex="0">
                                        <div class="text-center">
                                            <i class="bi bi-eye display-5" style="color: var(--ms-blue);"></i>
                                            <div class="small fw-semibold mt-1" style="color: var(--ms-blue);">Cliquer pour voir l'aperçu</div>
                                        </div>
                                    </div>
                                @endauth
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
                                    <i class="bi bi-eye me-1"></i>Voir et télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">Aucun document publié pour le moment.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('sujet.front.index') }}" class="modern-btn">
                <i class="bi bi-arrow-right me-2"></i>Voir Tous les Documents
            </a>
        </div>
    </div>
</section>
{{-- La modale "connexion requise" est partagée, définie une seule fois dans le layout (front_app.blade.php). --}}
