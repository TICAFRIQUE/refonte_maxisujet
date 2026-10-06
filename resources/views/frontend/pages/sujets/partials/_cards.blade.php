@forelse($sujets as $sujet)
    @include('frontend.pages.sujets.partials._card', ['sujet' => $sujet])
@empty
    <div class="col-12">
        <div class="empty-state detail-card">
            <i class="bi bi-search"></i>
            <h3>Aucun sujet ne correspond à ces critères</h3>
            <p class="mb-3">Essayez un autre mot-clé ou retirez un filtre.</p>
            <a href="{{ route('sujet.front.index') }}" class="btn btn-outline-primary">Voir tous les sujets</a>
        </div>
    </div>
@endforelse
