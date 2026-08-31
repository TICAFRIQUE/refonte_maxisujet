<div class="row g-4">
    <!-- Carte 1 - S'inscrire -->
    <div class="col-md-4">
        <div class="feature-card text-center h-100">
            <div class="mb-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width: 80px; height: 80px; background: var(--ms-blue-light);">
                    <i class="bi bi-person-plus-fill display-6" style="color: var(--ms-blue);"></i>
                </div>
            </div>
            <h5 class="card-title fw-bold mb-3">S'inscrire et devenir membre</h5>
            <p class="card-text text-muted mb-4">
                Rejoignez notre communauté et gagnez <strong>50 points</strong> immédiatement !
            </p>
            <a href="{{ route('user.registerForm') }}" class="btn btn-primary px-4">
                <i class="bi bi-rocket-takeoff me-2"></i>Commencer
            </a>
        </div>
    </div>

    <!-- Carte 2 - Poster -->
    <div class="col-md-4">
        <div class="feature-card text-center h-100">
            <div class="mb-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width: 80px; height: 80px; background: var(--ms-orange-light);">
                    <i class="bi bi-cloud-upload display-6" style="color: var(--ms-orange);"></i>
                </div>
            </div>
            <h5 class="card-title fw-bold mb-3">Publier un sujet</h5>
            <p class="card-text text-muted mb-4">
                Contribuez à la communauté et gagnez <strong>100 points</strong> par sujet approuvé.
            </p>
            <a href="{{ route('user.sujet.create') }}" class="btn btn-warning px-4">
                <i class="bi bi-share me-2"></i>Publier
            </a>
        </div>
    </div>

    <!-- Carte 3 - Découvrir -->
    <div class="col-md-4">
        <div class="feature-card text-center h-100">
            <div class="mb-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width: 80px; height: 80px; background: rgba(30, 58, 138, 0.1);">
                    <i class="bi bi-mortarboard display-6" style="color: var(--ms-navy);"></i>
                </div>
            </div>
            <h5 class="card-title fw-bold mb-3">Explorer les sujets</h5>
            <p class="card-text text-muted mb-4">
                Parcourez des milliers de sujets et corrigés pour préparer vos examens.
            </p>
            <a href="{{ route('sujet.front.index') }}" class="btn px-4" style="background: var(--ms-navy); color: #fff;">
                <i class="bi bi-compass me-2"></i>Découvrir
            </a>
        </div>
    </div>
</div>
