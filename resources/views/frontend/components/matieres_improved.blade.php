<!-- Matières : toutes visibles d'un coup, sous forme de tags, avec recherche rapide
     (remplace l'ancien carrousel qui n'affichait que 4 matières à la fois sur ~84 —
     il fallait cliquer des dizaines de fois pour toutes les parcourir). -->
<div class="matieres-block">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h3 class="mb-0">
            <i class="bi bi-collection text-primary me-2"></i>
            Explorez nos Matières
        </h3>

        <div class="matiere-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="matiere-search" placeholder="Rechercher une matière..." aria-label="Rechercher une matière">
        </div>
    </div>

    <div class="matiere-tags" id="matiere-tags">
        @foreach ($data_matieres->sortBy(fn($m) => Str::lower($m->libelle))->values() as $index => $matiere)
            @php
                $palette = ['var(--ms-orange)', 'var(--ms-blue)', 'var(--ms-navy)'];
                $soft = ['var(--ms-orange-light)', 'var(--ms-blue-light)', 'rgba(30, 58, 138, 0.1)'];
                $tone = $index % 3;
            @endphp
            <a href="{{ route('sujet.front.index', ['matiere' => $matiere->slug]) }}" class="matiere-tag"
               data-label="{{ Str::lower($matiere->libelle) }}"
               style="--tag-color: {{ $palette[$tone] }}; --tag-soft: {{ $soft[$tone] }};">
                {{ $matiere->libelle }}
            </a>
        @endforeach
    </div>

    <p id="matiere-empty" class="text-muted text-center mt-3" style="display:none;">
        Aucune matière ne correspond à votre recherche.
    </p>
</div>

@if ($data_matieres->count() > 8)
    <div class="text-center mt-5">
        <a href="{{ route('sujet.front.index') }}" class="modern-btn">
            <i class="bi bi-grid me-2"></i>Voir Toutes les Matières
        </a>
    </div>
@endif

<style>
    .matieres-block { margin: 1rem 0; }

    .matiere-search-wrap {
        position: relative;
        max-width: 280px;
        width: 100%;
    }

    .matiere-search-wrap i {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .matiere-search-wrap input {
        width: 100%;
        border: 2px solid var(--ms-border-subtle, #e2e8f0);
        border-radius: 20px;
        padding: 0.45rem 0.9rem 0.45rem 2.1rem;
        font-size: 0.85rem;
        transition: border-color 0.2s ease;
    }

    .matiere-search-wrap input:focus {
        outline: none;
        border-color: var(--ms-blue);
    }

    .matiere-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .matiere-tag {
        font-size: 0.85rem;
        font-weight: 400;
        padding: 0.4rem 0.9rem;
        border-radius: 18px;
        text-decoration: none;
        white-space: nowrap;
        background: var(--tag-soft);
        color: var(--tag-color);
        transition: background 0.15s ease, color 0.15s ease;
    }

    .matiere-tag:hover {
        background: var(--tag-color);
        color: #fff;
    }
</style>

<script>
    (function () {
        var input = document.getElementById('matiere-search');
        var tags = document.querySelectorAll('#matiere-tags .matiere-tag');
        var emptyMsg = document.getElementById('matiere-empty');

        input.addEventListener('input', function () {
            var query = input.value.trim().toLowerCase();
            var visibleCount = 0;

            tags.forEach(function (tag) {
                var matches = tag.dataset.label.includes(query);
                tag.hidden = !matches;
                if (matches) visibleCount++;
            });

            emptyMsg.style.display = visibleCount === 0 ? 'block' : 'none';
        });
    })();
</script>
