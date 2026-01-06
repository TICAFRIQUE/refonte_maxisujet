<!-- Matières avec design moderne -->
<div class="row g-4">
    @foreach($data_matieres as $matiere)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="feature-card text-center">
                <div class="mb-3">
                    @php
                        // Définir des icônes et couleurs pour chaque matière
                        $matiereIcons = [
                            'mathématiques' => ['icon' => 'bi-calculator', 'color' => '#ff6b6b'],
                            'physique' => ['icon' => 'bi-lightning', 'color' => '#4ecdc4'],
                            'chimie' => ['icon' => 'bi-droplet', 'color' => '#45b7d1'],
                            'français' => ['icon' => 'bi-book', 'color' => '#f7dc6f'],
                            'anglais' => ['icon' => 'bi-globe', 'color' => '#bb8fce'],
                            'histoire' => ['icon' => 'bi-clock-history', 'color' => '#f1948a'],
                            'géographie' => ['icon' => 'bi-globe-americas', 'color' => '#85c1e9'],
                            'svt' => ['icon' => 'bi-tree', 'color' => '#82e0aa'],
                            'sciences' => ['icon' => 'bi-atom', 'color' => '#fad7a0'],
                            'informatique' => ['icon' => 'bi-laptop', 'color' => '#a9def9']
                        ];
                        
                        $slug = strtolower($matiere->libelle);
                        $icon = 'bi-book';
                        $color = '#667eea';
                        
                        foreach($matiereIcons as $key => $data) {
                            if(str_contains($slug, $key)) {
                                $icon = $data['icon'];
                                $color = $data['color'];
                                break;
                            }
                        }
                    @endphp
                    
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                         style="width: 80px; height: 80px; background: linear-gradient(45deg, {{ $color }}, {{ $color }}33);">
                        <i class="bi {{ $icon }} display-5" style="color: {{ $color }};"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3" style="color: {{ $color }};">{{ $matiere->libelle }}</h5>
                
                <p class="text-muted mb-4">
                    Découvrez les ressources en {{ strtolower($matiere->libelle) }} pour tous les niveaux d'étude.
                </p>
                
                <a href="{{ route('sujet.front.index', ['matiere' => $matiere->slug]) }}" 
                   class="btn rounded-pill px-4" 
                   style="background: linear-gradient(45deg, {{ $color }}, {{ $color }}cc); color: white; border: none;">
                    <i class="bi bi-arrow-right me-2"></i>Explorer
                </a>
            </div>
        </div>
    @endforeach
</div>

@if($data_matieres->count() > 8)
    <div class="text-center mt-5">
        <a href="{{ route('sujet.front.index') }}" class="modern-btn">
            <i class="bi bi-grid me-2"></i>Voir Toutes les Matières
        </a>
    </div>
@endif