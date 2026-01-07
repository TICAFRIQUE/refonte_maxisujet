  <div class="row g-4">
            <!-- Carte 1 - S'inscrire -->
            <div class="col-md-4">
                <div class="card h-100 border-0 text-center stats-card scroll-animation position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(13, 110, 253, 0.1)); z-index: 0;"></div>
                    <div class="card-body position-relative" style="z-index: 1;">
                        <div class="mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-plus-fill text-primary display-6"></i>
                            </div>
                        </div>
                        <h5 class="card-title text-primary fw-bold mb-3">
                            S'inscrire et devenir membre
                        </h5>
                        <p class="card-text text-muted mb-4">
                            Rejoignez notre communauté et gagnez <span class="fw-bold text-warning badge bg-warning bg-opacity-10">50 points</span> immédiatement !
                        </p>
                        <a href="{{ route('user.registerForm') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
                            <i class="bi bi-rocket-takeoff me-2"></i>Commencer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carte 2 - Poster -->
            <div class="col-md-4">
                <div class="card h-100 border-0 text-center stats-card scroll-animation position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.05), rgba(255, 193, 7, 0.1)); z-index: 0;"></div>
                    <div class="card-body position-relative" style="z-index: 1;">
                        <div class="mb-4">
                            <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-cloud-upload text-warning display-6"></i>
                            </div>
                        </div>
                        <h5 class="card-title text-warning fw-bold mb-3">
                            Publier un document
                        </h5>
                        <p class="card-text text-muted mb-4">
                            Contribuez à la communauté et gagnez <span class="fw-bold text-primary badge bg-primary bg-opacity-10">5 points</span> par document partagé.
                        </p>
                        <a href="{{ route('user.sujet.create') }}" class="btn btn-warning btn-lg rounded-pill px-4 text-white shadow">
                            <i class="bi bi-share me-2"></i>Publier
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carte 3 - Découvrir -->
            <div class="col-md-4">
                <div class="card h-100 border-0 text-center stats-card scroll-animation position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(108, 117, 125, 0.05), rgba(108, 117, 125, 0.1)); z-index: 0;"></div>
                    <div class="card-body position-relative" style="z-index: 1;">
                        <div class="mb-4">
                            <div class="rounded-circle bg-dark bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-mortarboard text-dark display-6"></i>
                            </div>
                        </div>
                        <h5 class="card-title text-dark fw-bold mb-3">
                            Explorer les cours
                        </h5>
                        <p class="card-text text-muted mb-4">
                            Découvrez des milliers de ressources et boostez <span class="fw-bold text-success badge bg-success bg-opacity-10">vos compétences</span>.
                        </p>
                        <a href="{{ route('sujet.front.index') }}" class="btn btn-dark btn-lg rounded-pill px-4 shadow">
                            <i class="bi bi-compass me-2"></i>Découvrir
                        </a>
                    </div>
                </div>
            </div>
        </div>