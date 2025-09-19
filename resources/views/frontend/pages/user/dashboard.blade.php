@extends('frontend.layouts.front_app')

@section('content')
    <div class="container my-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
                <li class="breadcrumb-item"><a href="{{ route('accueil') }}" class="text-primary text-decoration-none"><i
                            class="bi bi-house-door"></i> Accueil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Nombre de téléchargements</h5>
                            </div>
                            <div class="card-body">
                                <h2 class="text-center">{{ $downloadsCount }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">Sujets publiés</h5>
                            </div>
                            <div class="card-body">
                                <h2 class="text-center">{{ $publishedSubjectsCount }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0">Nombre de points</h5>
                            </div>
                            <div class="card-body">
                                <h2 class="text-center">{{ $user->points ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            <strong>Participez à la communauté MaxiSujets !</strong><br>
                            En publiant des sujets, vous contribuez à enrichir notre plateforme et à aider d'autres
                            utilisateurs. De plus, chaque sujet que vous partagez vous permet de gagner des points, que vous
                            pourrez utiliser pour télécharger des ressources exclusives.
                        </p>
                        <div class="card shadow-sm mb-4">
                            <div
                                class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Mon profil</h5>
                                <span class="badge bg-success">{{ $user->points ?? 0 }} points</span>
                            </div>
                            <div class="card-body">

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('user.profile') }}" class="row g-3 needs-validation"
                                    novalidate>
                                    @csrf
                                    <div class="col-md-6">
                                        <label for="username" class="form-label">Nom d'utilisateur</label>
                                        <input type="text" name="username" id="username" class="form-control"
                                            value="{{ $user->username }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ $user->email }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Téléphone</label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ $user->phone }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profil" class="form-label">Profil</label>
                                        <select name="profil" id="profil" class="form-select" required>
                                            <option value="eleve" {{ $user->profil == 'eleve' ? 'selected' : '' }}>Élève
                                            </option>
                                            <option value="etudiant" {{ $user->profil == 'etudiant' ? 'selected' : '' }}>
                                                Étudiant(e)
                                            </option>
                                            <option value="enseignant"
                                                {{ $user->profil == 'enseignant' ? 'selected' : '' }}>
                                                Enseignant</option>
                                            <option value="parent" {{ $user->profil == 'parent' ? 'selected' : '' }}>Parent
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="password" class="form-label">Nouveau mot de passe <span
                                                class="text-muted">(laisser vide pour ne pas changer)</span></label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            autocomplete="new-password">
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Historique de mes téléchargements</h5>
                        </div>
                        <div class="card-body">
                            @if ($downloads->isEmpty())
                                <p class="text-muted">Aucun sujet téléchargé pour le moment.</p>
                            @else
                                <ul class="list-group">
                                    @foreach ($downloads as $download)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $download->sujet->libelle ?? 'Sujet inconnu' }}</span>
                                            <span
                                                class="badge bg-secondary">{{ $download->created_at->format('d/m/Y H:i') }}</span>
                                            <a href="{{ route('sujet.front.show', $download->sujet->libelle) }}"
                                                class="btn btn-sm btn-outline-primary">Voir le sujet</a>
                                        </li>
                                    @endforeach
                                    {{ $downloads->links() }}
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
                </div>
                
                <div class="text-center">
                    <a href="{{ route('user.sujet.create') }}" class="btn btn-warning btn-lg"><i
                            class="bi bi-plus-circle"></i>
                        Publier un sujet</a>
                </div>
            </div>
        </div>
    </div>
@endsection
