@extends('frontend.layouts.front_app')

@section('content')
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded shadow-sm p-4">
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}" class="text-primary text-decoration-none">
                    <i class="bi bi-house-door"></i> Accueil
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Connexion</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Connexion</h4>
                    <span>Connectez-vous pour accéder à votre espace.</span>
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

                    <form method="POST" action="{{ route('user.login') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="login" class="form-label">Email ou Nom d'utilisateur</label>
                            <input type="text" name="login" id="login" class="form-control" required value="{{ old('login') }}" placeholder="email@exemple.com ou pseudo">
                        </div>

                        <div class="mb-1">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('password.request') }}" class="small">Mot de passe oublié ?</a>
                        </div>

                        {{-- <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div> --}}

                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                </div>
                <div class="card-footer text-center bg-white">
                    <small class="text-muted">
                        Pas encore de compte ?
                        <a href="{{ route('user.registerForm') }}" class="text-primary text-decoration-none">Inscrivez-vous</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection