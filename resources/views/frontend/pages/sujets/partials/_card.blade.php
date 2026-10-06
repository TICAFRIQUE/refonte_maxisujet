@php
    $media = $sujet->getFirstMedia('non_corrige');
    $extension = $media ? strtolower($media->extension) : null;
    $isPdf = $extension === 'pdf';
    $isDoc = in_array($extension, ['doc', 'docx']);
    $hasCorrige = (bool) $sujet->getFirstMedia('corrige');

    // Le libellé est généré automatiquement (catégorie + code aléatoire) : il n'aide pas à choisir.
    // On titre donc la carte avec ce que l'élève cherche vraiment : la matière, à défaut le niveau.
    $niveauxCarte = $sujet->niveaux;
    $titreCarte = $sujet->matiere->libelle ?? null;
    if (!$titreCarte && $niveauxCarte->isNotEmpty()) {
        $titreCarte = $niveauxCarte->first()->libelle;
        $niveauxCarte = $niveauxCarte->slice(1)->values(); // déjà affiché en titre
    }
    $titreCarte = $titreCarte ?: ($sujet->categorie->libelle ?? $sujet->libelle);
@endphp
<div class="col-12 col-md-6 col-xl-4">
    <article class="subject-card">
        <div class="sujet-file {{ $isPdf ? 'sujet-file-pdf' : ($isDoc ? 'sujet-file-doc' : '') }}" aria-hidden="true">
            <i class="bi {{ $isPdf ? 'bi-file-earmark-pdf' : ($isDoc ? 'bi-file-earmark-word' : 'bi-file-earmark-text') }}"></i>
            @if ($extension)
                <small>{{ strtoupper($extension) }}</small>
            @endif
        </div>
        <div class="sujet-body">
            <div class="sujet-eyebrow">
                <span class="text-truncate">{{ $sujet->categorie->libelle ?? 'Sujet' }}@if ($sujet->annee) · {{ $sujet->annee }}@endif</span>
                <span class="sujet-ref" title="Code du sujet">{{ $sujet->code }}</span>
            </div>

            <h3 class="sujet-title">
                <a href="{{ route('sujet.front.show', $sujet->libelle) }}" class="stretched-link">{{ $titreCarte }}</a>
            </h3>

            <div class="sujet-chips">
                @forelse ($niveauxCarte->take(2) as $niveau)
                    <span class="chip">{{ $niveau->libelle }}</span>
                @empty
                    @if ($sujet->niveaux->isEmpty())
                        <span class="chip">Tous niveaux</span>
                    @endif
                @endforelse
                @if ($niveauxCarte->count() > 2)
                    <span class="chip" title="{{ $niveauxCarte->skip(2)->pluck('libelle')->implode(', ') }}">+{{ $niveauxCarte->count() - 2 }}</span>
                @endif
                @if ($hasCorrige)
                    <span class="chip chip-success"><i class="bi bi-check-circle-fill"></i>Corrigé</span>
                @endif
            </div>

            <div class="sujet-foot">
                @auth
                    @if (auth()->user()->points > 0)
                        <span class="sujet-cost is-points"><i class="bi bi-star-fill"></i>1 point</span>
                    @else
                        <span class="sujet-cost is-empty"><i class="bi bi-exclamation-triangle"></i>Points insuffisants</span>
                    @endif
                @else
                    <span class="sujet-cost"><i class="bi bi-lock"></i>Connexion requise</span>
                @endauth
                <span class="sujet-more" aria-hidden="true">Voir <i class="bi bi-arrow-right-short"></i></span>
            </div>
        </div>
    </article>
</div>
