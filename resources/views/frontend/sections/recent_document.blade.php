<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Derniers Documents</h2>
            <p class="lead text-muted">Découvrez les derniers ajouts à notre bibliothèque</p>
        </div>
        @include('frontend.pages.sujets.partials._card-styles')
        <div class="row g-3">
            @include('frontend.pages.sujets.partials._cards', ['sujets' => $sujetsRecents])
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('sujet.front.index') }}" class="modern-btn">
                <i class="bi bi-arrow-right me-2"></i>Voir Tous les Documents
            </a>
        </div>
    </div>
</section>
{{-- La modale "connexion requise" est partagée, définie une seule fois dans le layout (front_app.blade.php). --}}
