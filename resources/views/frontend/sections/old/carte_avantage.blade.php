  <section class="container my-5">
        <div class="row g-4">
            <!-- Carte 1 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body bg-light rounded">
                        <h5 class="card-title text-primary fw-bold">
                            <i class="bi bi-person-plus-fill"></i> S’inscrire et devenir membre
                        </h5>
                        <p class="card-text">Gagnez <span class="fw-bold text-warning">50 points</span> en rejoignant
                            la plateforme.</p>
                        <a href="{{ route('user.registerForm') }}" class="btn btn-primary w-100">S’inscrire</a>
                    </div>
                </div>
            </div>

            <!-- Carte 2 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body bg-light rounded">
                        <h5 class="card-title text-warning fw-bold">
                            <i class="bi bi-upload"></i> Poster un sujet
                        </h5>
                        <p class="card-text">Partagez un document et gagnez <span class="fw-bold text-primary">5
                                points</span>.</p>
                        <a href="{{ route('user.sujet.create') }}" class="btn btn-warning w-100 text-white">Poster</a>
                    </div>
                </div>
            </div>

            <!-- Carte 3 -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <div class="card-body bg-light rounded">
                        <h5 class="card-title text-dark fw-bold">
                            <i class="bi bi-question-circle-fill"></i> Cours & Exercices
                        </h5>
                        <p class="card-text">Testez vos compétences et gagnez <span class="fw-bold text-warning">50
                                points</span>.</p>
                        <a href="#" class="btn btn-dark w-100">Commencer</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
