@extends('frontend.layouts.front_app')

@section('content')
    <div class="container">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            @include('frontend.components.retour')
        <nav aria-label="Fil d'Ariane" class="mb-0 flex-grow-1 min-w-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Connexion</li>
            </ol>
        </nav>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card">
                    <div class="card-header auth-header">
                        <h4 class="mb-2">Bon retour !</h4>
                        <p class="mb-0">Connectez-vous pour accéder à votre espace</p>
                    </div>
                    <div class="card-body auth-body">
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
                                <label for="login" class="form-label">
                                    <i class="bi bi-person-circle me-2"></i>Email ou Nom d'utilisateur
                                </label>
                                <input type="text" name="login" id="login" class="form-control" required
                                    value="{{ old('login') }}" placeholder="email@exemple.com ou pseudo">
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>Mot de passe
                                </label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="password" class="form-control pe-5" required
                                        placeholder="Votre mot de passe">
                                    <button type="button" id="togglePassword" 
                                            class="btn position-absolute top-50 end-0 translate-middle-y me-2"
                                            style="border: none; background: none; color: #6b7280; z-index: 10; padding: 0.5rem;"
                                            title="Afficher/Masquer le mot de passe">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mb-4">
                                <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                            </div>

                            <button type="submit" class="btn btn-auth w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-center" style="background: var(--ms-bg-soft); border-top: 1px solid var(--ms-border-subtle);">
                        <p class="text-muted mb-0">
                            Pas encore de compte ?
                            <a href="{{ route('user.registerForm') }}" class="auth-link">Inscrivez-vous gratuitement</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', function() {
                // Basculer le type d'input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Changer l'icône
                if (type === 'text') {
                    eyeIcon.classList.remove('bi-eye');
                    eyeIcon.classList.add('bi-eye-slash');
                    togglePassword.title = 'Masquer le mot de passe';
                } else {
                    eyeIcon.classList.remove('bi-eye-slash');
                    eyeIcon.classList.add('bi-eye');
                    togglePassword.title = 'Afficher le mot de passe';
                }
            });
        });
    </script>
@endsection
