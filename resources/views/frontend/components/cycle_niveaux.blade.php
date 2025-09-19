<section class="container my-5">
<div class="mb-4 text-center">
    <h2 class="text-center text-primary">Cycles & Niveaux</h2>
    <span class="d-block text-center">Parcourez nos cycles pour trouver ce qui vous interesse.</span>
</div>
    <div class="row text-center g-4">
        @foreach($data_niveaux as $cycle)
            <div class="col-12 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">
                            <i class="bi {{ $cycle->icon ?? 'bi-book' }}"></i> {{ $cycle->libelle }}
                        </h5>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach($cycle->children as $niveau)
                                <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['niveau' => $niveau->slug])) }}"
                                   class="badge bg-light text-dark border text-decoration-none">
                                    {{ $niveau->libelle }}
                                </a>
                                @if($niveau->children && $niveau->children->count())
                                    @foreach($niveau->children as $subNiveau)
                                        <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['niveau' => $subNiveau->slug])) }}"
                                           class="badge bg-light text-dark border ms-2 text-decoration-none">
                                            &raquo; {{ $subNiveau->libelle }}
                                        </a>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>