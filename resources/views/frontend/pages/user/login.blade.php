@extends('frontend.layouts.front_app')

@section('content')
    <style>
        .auth-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%) !important;
            padding: 2rem 1.5rem;
        }

        .auth-body {
            padding: 2rem 1.5rem;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.1);
        }

        .btn-auth {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .auth-link {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-link:hover {
            color: #e55a2b;
            text-decoration: underline;
        }

        .forgot-link {
            color: #6b7280;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #ff6b35;
        }

        .alert {
            border: none;
            border-radius: 10px;
        }
    </style>
    <div class="container my-2">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light rounded p-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Connexion</li>
            </ol>
        </nav>


        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card">
                    <div class="card-header auth-header bg-primary text-white text-center">
                        <h4 class="mb-2">Bon retour !</h4>
                        <p class="mb-0 opacity-90">Connectez-vous pour accéder à votre espace</p>
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

                            <button type="submit" class="btn btn-auth text-white w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-center" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
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
