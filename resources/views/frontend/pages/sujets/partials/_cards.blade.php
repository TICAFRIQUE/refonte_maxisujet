@forelse($sujets as $sujet)
    @include('frontend.pages.sujets.partials._card', ['sujet' => $sujet])
@empty
    <div class="col-12">
        <div class="alert alert-info d-flex align-items-center gap-2">
            <i class="bi bi-emoji-frown fs-4"></i>
            <div>
                Aucun sujet ne correspond à ces critères.
                <a href="{{ route('sujet.front.index') }}">Réinitialiser les filtres</a>.
            </div>
        </div>
    </div>
@endforelse
