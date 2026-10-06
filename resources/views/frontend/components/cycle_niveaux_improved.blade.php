{{-- Cycles et niveaux : un bloc par cycle, niveaux sous forme d'étiquettes cliquables.
     Au-delà de 8 niveaux, la liste est repliée (un cycle comme "Université" en compte plus de 30). --}}
<div class="row g-3 align-items-start">
    @foreach ($data_niveaux as $cycle)
        @php
            // Icône adaptée au cycle
            $cycleIcons = [
                'primaire' => 'bi-backpack',
                'collège' => 'bi-book',
                'college' => 'bi-book',
                'lycée' => 'bi-journal-bookmark',
                'lycee' => 'bi-journal-bookmark',
                'secondaire' => 'bi-journal-bookmark',
                'supérieur' => 'bi-mortarboard',
                'université' => 'bi-mortarboard',
                'concours' => 'bi-trophy',
            ];

            $cycleSlug = Str::lower($cycle->libelle);
            $cycleIcon = 'bi-book';
            foreach ($cycleIcons as $key => $iconName) {
                if (str_contains($cycleSlug, $key)) {
                    $cycleIcon = $iconName;
                    break;
                }
            }

            // Niveaux et sous-niveaux à plat, dans l'ordre d'affichage
            $cycleNiveaux = collect();
            foreach ($cycle->children as $niveau) {
                $cycleNiveaux->push($niveau);
                foreach ($niveau->children as $subNiveau) {
                    $cycleNiveaux->push($subNiveau);
                }
            }
            $cycleLimite = 8;
            $cycleReplie = $cycleNiveaux->count() > $cycleLimite;
        @endphp
        <div class="col-md-6 col-xl-4">
            <div class="cycle-block">
                <div class="cycle-head">
                    <span class="cycle-icon" aria-hidden="true"><i class="bi {{ $cycleIcon }}"></i></span>
                    <div>
                        <h3>{{ Str::title(Str::lower($cycle->libelle)) }}</h3>
                        <small>{{ $cycleNiveaux->count() }} niveau{{ $cycleNiveaux->count() > 1 ? 'x' : '' }}</small>
                    </div>
                </div>

                <div class="tag-list {{ $cycleReplie ? 'is-collapsed' : '' }}">
                    @foreach ($cycleNiveaux as $i => $niveau)
                        <a href="{{ route('sujet.front.index', ['niveau' => $niveau->slug]) }}"
                            class="tag-link {{ $i >= $cycleLimite ? 'tag-extra' : '' }}">{{ $niveau->libelle }}</a>
                    @endforeach
                </div>

                @if ($cycleReplie)
                    <button type="button" class="tag-toggle" data-tag-toggle aria-expanded="false"
                        data-label-more="Afficher les {{ $cycleNiveaux->count() - $cycleLimite }} autres niveaux"
                        data-label-less="Réduire la liste">
                        Afficher les {{ $cycleNiveaux->count() - $cycleLimite }} autres niveaux
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>

