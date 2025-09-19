<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\index.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Éducative - Téléchargement de Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .navbar { background: #2563eb; }
        .navbar .nav-link, .navbar .navbar-brand { color: #fff !important; }
        .navbar .btn { margin-left: 0.5rem; }
        .card { border-radius: 1rem; }
        .footer { background: #1e293b; color: #fff; }
        .footer a { color: #fff; text-decoration: underline; }
        .carousel-caption { background: rgba(37,99,235,0.7); border-radius: 1rem; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-mortarboard"></i> EduDocs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Catégories -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="catDropdown" role="button" data-bs-toggle="dropdown">
                            Catégories
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Cours</a></li>
                            <li><a class="dropdown-item" href="#">Devoirs</a></li>
                            <li><a class="dropdown-item" href="#">Examens</a></li>
                            <li><a class="dropdown-item" href="#">Concours</a></li>
                        </ul>
                    </li>
                    <!-- Cycles -->
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle" href="#" id="cycleDropdown" role="button" data-bs-toggle="dropdown">
                            Cycles
                        </a>
                        <div class="dropdown-menu w-100 mt-0 p-3" aria-labelledby="cycleDropdown" style="min-width:600px;">
                            <div class="row text-center">
                                <div class="col-12 col-md-3 mb-2">
                                    <strong class="text-primary">Primaire</strong>
                                    <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                                        <a class="dropdown-item px-2 py-1" href="#">CP1</a>
                                        <a class="dropdown-item px-2 py-1" href="#">CP2</a>
                                        <a class="dropdown-item px-2 py-1" href="#">CE1</a>
                                        <a class="dropdown-item px-2 py-1" href="#">CE2</a>
                                        <a class="dropdown-item px-2 py-1" href="#">CM1</a>
                                        <a class="dropdown-item px-2 py-1" href="#">CM2</a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <strong class="text-primary">Collège</strong>
                                    <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                                        <a class="dropdown-item px-2 py-1" href="#">6e</a>
                                        <a class="dropdown-item px-2 py-1" href="#">5e</a>
                                        <a class="dropdown-item px-2 py-1" href="#">4e</a>
                                        <a class="dropdown-item px-2 py-1" href="#">3e</a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <strong class="text-primary">Lycée</strong>
                                    <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                                        <a class="dropdown-item px-2 py-1" href="#">2nde</a>
                                        <a class="dropdown-item px-2 py-1" href="#">1ère</a>
                                        <a class="dropdown-item px-2 py-1" href="#">Terminale</a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3 mb-2">
                                    <strong class="text-primary">Université</strong>
                                    <div class="d-flex flex-wrap justify-content-center gap-1 mt-2">
                                        <a class="dropdown-item px-2 py-1" href="#">Licence 1</a>
                                        <a class="dropdown-item px-2 py-1" href="#">Licence 2</a>
                                        <a class="dropdown-item px-2 py-1" href="#">Licence 3</a>
                                        <a class="dropdown-item px-2 py-1" href="#">Master 1</a>
                                        <a class="dropdown-item px-2 py-1" href="#">Master 2</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
                <div class="d-flex">
                    <a href="#" class="btn btn-outline-light me-2">Connexion</a>
                    <a href="#" class="btn btn-warning">S’inscrire</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Carousel -->
    <div id="mainCarousel" class="carousel slide mt-5 pt-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=900&q=80" class="d-block w-100" alt="Éducation" style="height:280px;object-fit:cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h3>Bienvenue sur EduDocs</h3>
                    <p>Plateforme de téléchargement de documents scolaires et universitaires.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=900&q=80" class="d-block w-100" alt="Cours" style="height:280px;object-fit:cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h3>Des milliers de ressources</h3>
                    <p>Cours, devoirs, examens et concours pour tous les niveaux.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=900&q=80" class="d-block w-100" alt="Réussite" style="height:280px;object-fit:cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h3>Réussissez vos études</h3>
                    <p>Accédez facilement aux documents et astuces pour progresser.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Cycles horizontal -->
    <section class="container my-5">
        <h2 class="mb-4 text-center text-primary">Cycles</h2>
        <div class="row text-center g-4">
            <div class="col-12 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Primaire</h5>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge bg-light text-dark border">CP1</span>
                            <span class="badge bg-light text-dark border">CP2</span>
                            <span class="badge bg-light text-dark border">CE1</span>
                            <span class="badge bg-light text-dark border">CE2</span>
                            <span class="badge bg-light text-dark border">CM1</span>
                            <span class="badge bg-light text-dark border">CM2</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Collège</h5>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge bg-light text-dark border">6e</span>
                            <span class="badge bg-light text-dark border">5e</span>
                            <span class="badge bg-light text-dark border">4e</span>
                            <span class="badge bg-light text-dark border">3e</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Lycée</h5>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge bg-light text-dark border">2nde</span>
                            <span class="badge bg-light text-dark border">1ère</span>
                            <span class="badge bg-light text-dark border">Terminale</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Université</h5>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge bg-light text-dark border">Licence 1</span>
                            <span class="badge bg-light text-dark border">Licence 2</span>
                            <span class="badge bg-light text-dark border">Licence 3</span>
                            <span class="badge bg-light text-dark border">Master 1</span>
                            <span class="badge bg-light text-dark border">Master 2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Derniers documents -->
    <section class="container my-5">
        <h2 class="mb-4 text-center text-primary">📚 Derniers documents</h2>
        <div class="row g-4">
            @for($i=1; $i<=6; $i++)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Document {{ $i }}</h5>
                        <p class="card-text">Description du document {{ $i }}. Ce document est utile pour réviser et réussir vos examens.</p>
                        <span class="badge bg-info mb-2">Catégorie : Cours</span>
                        <span class="badge bg-secondary mb-2">Cycle : Terminale</span>
                        <a href="#" class="btn btn-primary w-100"><i class="bi bi-download"></i> Télécharger</a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </section>

    <!-- Blog & Astuces -->
    <section class="container my-5">
        <h2 class="mb-4 text-center text-primary">Blog & Astuces</h2>
        <div class="row g-4">
            @for($i=1; $i<=3; $i++)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80" class="card-img-top" alt="Blog">
                    <div class="card-body">
                        <h5 class="card-title">Astuce {{ $i }}</h5>
                        <p class="card-text">Découvrez nos conseils pour réussir vos études et optimiser votre apprentissage.</p>
                        <a href="#" class="btn btn-outline-primary">Lire plus</a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-auto py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#">Cours</a></li>
                        <li><a href="#">Examens</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Coordonnées</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope"></i> contact@edudocs.com</li>
                        <li><i class="bi bi-telephone"></i> +33 1 23 45 67 89</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Réseaux sociaux</h5>
                    <a href="#" class="me-2"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="me-2"><i class="bi bi-whatsapp fs-4"></i></a>
                    <a href="#" class="me-2"><i class="bi bi-instagram fs-4"></i></a>
                </div>
            </div>
            <div class="text-center mt-3">
                &copy; {{ date('Y') }} EduDocs. Tous droits réservés.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>