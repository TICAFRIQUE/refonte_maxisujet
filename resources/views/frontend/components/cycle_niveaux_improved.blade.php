<!-- Cycles et Niveaux avec design moderne -->
<div class="row g-4">
    @foreach($data_niveaux as $cycle)
        <div class="col-lg-6 col-md-12">
            <div class="feature-card h-100">
                <!-- Header du Cycle -->
                <div class="d-flex align-items-center mb-4">
                    @php
                        // Définir des icônes et couleurs pour chaque cycle
                        $cycleStyles = [
                            'primaire' => ['icon' => 'bi-house-heart', 'color' => '#ff9ff3', 'bg' => 'linear-gradient(45deg, #ff9a9e, #fecfef)'],
                            'secondaire' => ['icon' => 'bi-mortarboard', 'color' => '#4facfe', 'bg' => 'linear-gradient(45deg, #4facfe, #00f2fe)'],
                            'supérieur' => ['icon' => 'bi-award', 'color' => '#43e97b', 'bg' => 'linear-gradient(45deg, #43e97b, #38f9d7)'],
                            'université' => ['icon' => 'bi-building', 'color' => '#fa709a', 'bg' => 'linear-gradient(45deg, #fa709a, #fee140)'],
                            'concours' => ['icon' => 'bi-trophy', 'color' => '#ffecd2', 'bg' => 'linear-gradient(45deg, #fcb045, #fd1d1d)']
                        ];
                        
                        $slug = strtolower($cycle->libelle);
                        $style = ['icon' => 'bi-book', 'color' => '#667eea', 'bg' => 'linear-gradient(45deg, #667eea, #764ba2)'];
                        
                        foreach($cycleStyles as $key => $data) {
                            if(str_contains($slug, $key)) {
                                $style = $data;
                                break;
                            }
                        }
                    @endphp
                    
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px; background: {{ $style['bg'] }};">
                        <i class="bi {{ $style['icon'] ?? 'bi-book' }} text-white display-6"></i>
                    </div>
                    
                    <div>
                        <h4 class="fw-bold mb-1" style="color: {{ $style['color'] }};">{{ $cycle->libelle }}</h4>
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
                                   class="btn btn-sm rounded-pill px-3" 
                                   style="background: {{ $style['color'] }}; color: white; border: none;">
                                    <i class="bi bi-arrow-right me-1"></i>Voir les cours
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