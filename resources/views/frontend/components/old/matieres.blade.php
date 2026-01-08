<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\components\matieres.blade.php -->
<section class="container my-5">
    <div class="mb-4 text-center">
        <h2 class="text-center text-primary">Matières</h2>
        <span class="d-block text-center">Parcourez nos matières pour trouver ce qui vous intéresse.</span>
    </div>
    <div class="row text-center g-4">
        @foreach($data_matieres as $matiere)
            <div class="col-12 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['matiere' => $matiere->slug])) }}"
                               class="badge text-dark border text-decoration-none {{request('matiere') == $matiere->slug ? 'bg-success text-white' : 'bg-light'}}">
                                <i class="bi bi-book"></i> {{ $matiere->libelle }}
                            </a>
                        </div>
                </div>
            </div>
        @endforeach
    </div>
</section>