<!-- Cycles et Niveaux -->
<div class="row g-4">
    @foreach($data_niveaux as $cycleIndex => $cycle)
        <div class="col-lg-6 col-md-12">
            <div class="feature-card h-100">
                <!-- Header du Cycle -->
                <div class="d-flex align-items-center mb-4">
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
                            ['solid' => '#ff6b35', 'bg' => 'var(--ms-orange)'],
                            ['solid' => '#0d6efd', 'bg' => 'var(--ms-blue)'],
                            ['solid' => '#1e3a8a', 'bg' => 'var(--ms-navy)'],
                        ];
                        $tone = $palette[$cycleIndex % 3];
                        $style = ['icon' => $icon, 'color' => $tone['solid'], 'bg' => $tone['bg']];
                    @endphp

                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                         style="width: 60px; height: 60px; background: {{ $style['bg'] }};">
                        <i class="bi {{ $style['icon'] ?? 'bi-book' }} text-white display-6"></i>
                    </div>

                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--ms-ink);">{{ $cycle->libelle }}</h4>
                        <p class="text-muted mb-0">{{ $cycle->children->count() }} niveaux disponibles</p>
                    </div>
                </div>
                
                <!-- Niveaux du Cycle -->
                <div class="row g-3">
                    @foreach($cycle->children as $niveau)
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100" style="border-color: {{ $style['color'] }}33 !important; background: {{ $style['color'] }}08;">
                                <h6 class="fw-bold mb-2" style="color: {{ $style['color'] }};">{{ $niveau->libelle }}</h6>
                                
                                @if($niveau->children && $niveau->children->count())
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach($niveau->children->take(3) as $subNiveau)
                                            <span class="badge" style="background-color: {{ $style['color'] }}22; color: {{ $style['color'] }};">
                                                {{ $subNiveau->libelle }}
                                            </span>
                                        @endforeach
                                        @if($niveau->children->count() > 3)
                                            <span class="badge" style="background-color: {{ $style['color'] }}22; color: {{ $style['color'] }};">
                                                +{{ $niveau->children->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <a href="{{ route('sujet.front.index', ['niveau' => $niveau->slug]) }}"
                                   class="btn btn-sm px-3"
                                   style="background: {{ $style['color'] }}; color: white; border: none;">
                                    <i class="bi bi-arrow-right me-1"></i>Voir les sujets
                                </a>
                            </div>
                        </div>
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