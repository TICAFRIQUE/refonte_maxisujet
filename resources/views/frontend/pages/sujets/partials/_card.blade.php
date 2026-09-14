@php
    $media = $sujet->getFirstMedia('non_corrige');
    $extension = $media ? strtolower($media->extension) : null;
    $isPdf = $extension === 'pdf';
    $isDoc = in_array($extension, ['doc', 'docx']);
@endphp
<div class="col-12 col-sm-6 col-lg-4">
    <div class="card subject-card-h h-100">
        <div class="d-flex h-100">
            <div class="subject-preview flex-shrink-0 d-flex align-items-center justify-content-center bg-light">
                @if ($media && $isPdf)
                    <iframe src="{{ route('sujet.front.apercu', ['id' => $sujet->id, 'type' => 'non_corrige']) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                        style="position:absolute; top:0; left:0; width: 340px; height: 340px; border: none; transform: scale(0.24); transform-origin: top left; pointer-events: none;"
                        tabindex="-1" title="Aperçu du sujet" loading="lazy"></iframe>
                @elseif ($isDoc)
                    <i class="bi bi-filetype-doc text-primary"></i>
                @else
                    <i class="bi bi-file-earmark-text text-muted"></i>
                @endif
            </div>
            <div class="subject-info flex-grow-1 min-w-0 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start gap-1">
                    <h6 class="subject-title-sm mb-0 text-truncate" title="{{ $sujet->libelle }}">
                        {{ Str::limit($sujet->libelle, 26) }}
                    </h6>
                    <span class="badge-code-sm">{{ $sujet->code }}</span>
                </div>

                <div class="subject-meta-sm d-flex flex-wrap gap-1 my-1">
                    @if ($sujet->matiere)
                        <span class="tag-sm tag-matiere">{{ Str::limit($sujet->matiere->libelle, 14) }}</span>
                    @endif
                    @if ($sujet->niveaux->count() > 0)
                        <span class="tag-sm tag-niveau">{{ Str::limit($sujet->niveaux->first()->libelle, 12) }}</span>
                        @if ($sujet->niveaux->count() > 1)
                            <span class="tag-sm tag-niveau">+{{ $sujet->niveaux->count() - 1 }}</span>
                        @endif
                    @else
                        <span class="tag-sm tag-niveau">Tous niveaux</span>
                    @endif
                    <span class="tag-sm tag-annee">{{ $sujet->annee }}</span>
                </div>

                <div class="mt-auto d-flex align-items-center justify-content-between gap-1">
                    <a href="{{ route('sujet.front.show', $sujet->libelle) }}" class="link-details-sm stretched-link">
                        Détails <i class="bi bi-arrow-right-short"></i>
                    </a>
                    @auth
                        @if (auth()->user()->points > 0)
                            <span class="cost-tag-sm" title="1 point par téléchargement"><i class="bi bi-star-fill"></i> 1 pt</span>
                        @else
                            <span class="cost-tag-sm text-danger" title="Points insuffisants"><i class="bi bi-exclamation-triangle"></i></span>
                        @endif
                    @else
                        <span class="cost-tag-sm" title="Connexion requise"><i class="bi bi-lock"></i></span>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
