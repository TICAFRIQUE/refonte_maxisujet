{{-- Matières : étiquettes cliquables avec recherche rapide. Les 24 premières sont visibles,
     le reste se déplie à la demande (ou automatiquement dès qu'on tape une recherche) —
     afficher les ~80 matières d'un coup formait un mur d'étiquettes illisible. --}}
@php
    $matieresTriees = $data_matieres->sortBy(fn($m) => Str::lower($m->libelle))->values();
    $matieresLimite = 24;
    $matieresRepliees = $matieresTriees->count() > $matieresLimite;
@endphp
<div class="matieres-block">
    <div class="matiere-search-wrap mb-3">
        <i class="bi bi-search" aria-hidden="true"></i>
        <input type="search" id="matiere-search" placeholder="Filtrer les matières…" aria-label="Filtrer les matières">
    </div>

    <div class="tag-list {{ $matieresRepliees ? 'is-collapsed' : '' }}" id="matiere-tags">
        @foreach ($matieresTriees as $index => $matiere)
            <a href="{{ route('sujet.front.index', ['matiere' => $matiere->slug]) }}"
                class="tag-link {{ $index >= $matieresLimite ? 'tag-extra' : '' }}"
                data-label="{{ Str::lower($matiere->libelle) }}">{{ $matiere->libelle }}</a>
        @endforeach
    </div>

    @if ($matieresRepliees)
        <button type="button" class="tag-toggle" id="matiere-toggle" data-tag-toggle aria-expanded="false"
            data-label-more="Afficher les {{ $matieresTriees->count() }} matières"
            data-label-less="Réduire la liste">
            Afficher les {{ $matieresTriees->count() }} matières
        </button>
    @endif

    <p id="matiere-empty" class="text-muted mt-3 mb-0" hidden>
        Aucune matière ne correspond à votre recherche.
    </p>
</div>

<script>
    (function () {
        var input = document.getElementById('matiere-search');
        var list = document.getElementById('matiere-tags');
        var tags = list.querySelectorAll('.tag-link');
        var toggle = document.getElementById('matiere-toggle');
        var emptyMsg = document.getElementById('matiere-empty');
        var normalize = function (s) { return s.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase(); };

        input.addEventListener('input', function () {
            var query = normalize(input.value.trim());
            var visibleCount = 0;

            // Pendant une recherche, on cherche dans toutes les matières : la liste se déplie.
            if (toggle) {
                list.classList.toggle('is-collapsed', query === '' && toggle.getAttribute('aria-expanded') !== 'true');
                toggle.hidden = query !== '';
            }

            tags.forEach(function (tag) {
                var matches = normalize(tag.dataset.label).includes(query);
                tag.hidden = !matches;
                if (matches) visibleCount++;
            });

            emptyMsg.hidden = visibleCount !== 0;
        });
    })();
</script>
