<!-- Cycles et Niveaux : tags compacts qui s'enchaînent (plus de liste verticale) -->
<style>
    .cycle-list-block { padding: 0.5rem 0; }

    .niveau-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .niveau-tag {
        font-size: 0.8rem;
        font-weight: 400;
        padding: 0.35rem 0.8rem;
        border-radius: 16px;
        text-decoration: none;
        white-space: nowrap;
        background: var(--tag-soft);
        color: var(--tag-color);
        transition: background 0.15s ease, color 0.15s ease;
    }

    .niveau-tag:hover {
        background: var(--tag-color);
        color: #fff;
    }

    .niveau-tag-sub {
        font-size: 0.74rem;
        opacity: 0.85;
    }
</style>

<div class="row g-4">
    @foreach($data_niveaux as $cycleIndex => $cycle)
        <div class="col-lg-6 col-md-12">
            <div class="cycle-list-block h-100">
                <!-- Header du Cycle -->
                <div class="d-flex align-items-center mb-3">
                    @php
                        // Icône adaptée au cycle ; couleur cyclée sur la palette de marque.
                        $cycleIcons = [
                            'primaire' => 'bi-house-heart',
                            'secondaire' => 'bi-mortarboard',
                            'supérieur' => 'bi-award',
                            'université' => 'bi-building',
                            'concours' => 'bi-trophy',
                        ];

                        $slug = strtolower($cycle->libelle);
                        $icon = 'bi-book';
                        foreach ($cycleIcons as $key => $iconName) {
                            if (str_contains($slug, $key)) {
                                $icon = $iconName;
                                break;
                            }
                        }

                        $palette = [
                            ['solid' => '#ff6b35', 'bg' => 'var(--ms-orange)', 'soft' => 'var(--ms-orange-light)'],
                            ['solid' => '#0d6efd', 'bg' => 'var(--ms-blue)', 'soft' => 'var(--ms-blue-light)'],
                            ['solid' => '#1e3a8a', 'bg' => 'var(--ms-navy)', 'soft' => '#e8eaf6'],
                        ];
                        $tone = $palette[$cycleIndex % 3];
                        $style = ['icon' => $icon, 'color' => $tone['solid'], 'bg' => $tone['bg'], 'soft' => $tone['soft']];
                    @endphp

                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width: 44px; height: 44px; background: {{ $style['bg'] }};">
                        <i class="bi {{ $style['icon'] ?? 'bi-book' }} text-white"></i>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-0" style="color: var(--ms-ink);">{{ $cycle->libelle }}</h5>
                        <small class="text-muted">{{ $cycle->children->count() }} niveaux disponibles</small>
                    </div>
                </div>

                <!-- Niveaux du Cycle, sous forme de tags qui s'enchaînent -->
                <div class="niveau-tags">
                    @foreach($cycle->children as $niveau)
                        <a href="{{ route('sujet.front.index', ['niveau' => $niveau->slug]) }}" class="niveau-tag"
                           style="--tag-color: {{ $style['color'] }}; --tag-soft: {{ $style['soft'] }};">
                            {{ $niveau->libelle }}
                        </a>

                        @foreach($niveau->children as $subNiveau)
                            <a href="{{ route('sujet.front.index', ['niveau' => $subNiveau->slug]) }}" class="niveau-tag niveau-tag-sub"
                               style="--tag-color: {{ $style['color'] }}; --tag-soft: {{ $style['soft'] }};">
                                {{ $subNiveau->libelle }}
                            </a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Bouton pour voir tous les niveaux -->
<div class="text-center mt-5">
    <a href="{{ route('sujet.front.index') }}" class="modern-btn">
        <i class="bi bi-layers me-2"></i>Voir Tous les Niveaux
    </a>
</div>
