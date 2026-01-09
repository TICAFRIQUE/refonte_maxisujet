<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\user\register.blade.php -->
@extends('frontend.layouts.front_app')

@section('content')
<style>
    .auth-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .auth-header {
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%) !important;
        padding: 2rem 1.5rem;
    }
    
    .auth-body {
        padding: 2rem 1.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
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
    
    .input-group .btn {
        border-radius: 0 10px 10px 0;
        border: 2px solid #e2e8f0;
        border-left: none;
    }
    
    .input-group .form-control {
        border-radius: 10px 0 0 10px;
        border-right: none;
    }
    
    .password-toggle:hover {
        background-color: #ff6b35;
        color: white;
        border-color: #ff6b35;
    }
    
    .alert {
        border: none;
        border-radius: 10px;
    }
    
    .form-check-input:checked {
        background-color: #ff6b35;
        border-color: #ff6b35;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.1);
    }
</style>
    <div class="container py-5 min-vh-100 d-flex flex-column">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light rounded p-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('accueil') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Inscription
                </li>
            </ol>
        </nav>
        <div class="row justify-content-center flex-grow-1">
            <div class="col-md-6 d-flex align-items-center">
                <div class="card auth-card">
                    <div class="card-header auth-header bg-primary text-white text-center">
                        <h4 class="mb-2">Rejoignez MaxiSujets</h4>
                        <p class="mb-0 opacity-90">Inscrivez-vous gratuitement pour accéder aux ressources et publier des sujets</p>
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

                        <form method="POST" action="{{ route('user.register') }}" class="needs-validation" novalidate id="registerForm">
                            @csrf

                            <div class="mb-3">
                                <label for="profil" class="form-label">
                                    <i class="bi bi-person-badge me-2"></i>Profil
                                </label>
                                <select name="profil" id="profil" class="form-select @error('profil') is-invalid @enderror" required autocomplete="off">
                                    <option value="">Sélectionner...</option>
                                    <option value="eleve" {{ old('profil') == 'eleve' ? 'selected' : '' }}>Élève</option>
                                    <option value="etudiant" {{ old('profil') == 'etudiant' ? 'selected' : '' }}>Etudiant(e)
                                    </option>
                                    <option value="enseignant" {{ old('profil') == 'enseignant' ? 'selected' : '' }}>
                                        Enseignant</option>
                                    <option value="parent" {{ old('profil') == 'parent' ? 'selected' : '' }}>Parent</option>
                                </select>
                                @error('profil')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person me-2"></i>Nom d'utilisateur
                                </label>
                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" required
                                    value="{{ old('username') }}" placeholder="Ex: johndoe" autocomplete="username">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone me-2"></i>Téléphone
                                </label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                    value="{{ old('phone') }}" placeholder="Ex: +2250700000000" autocomplete="tel">
                                <div class="form-text">Inclure l'indicatif international (ex: +33, +221, +225).</div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Adresse email
                                </label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required
                                    value="{{ old('email') }}" placeholder="email@exemple.com" autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>Mot de passe
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" minlength="8" placeholder="Au moins 8 caractères">
                                    <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePassword"><i class="bi bi-eye"></i></button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="passwordHelp">Utilisez une combinaison de lettres, chiffres et symboles.</div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div id="passwordStrength" class="progress-bar" role="progressbar"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-shield-check me-2"></i>Confirmer le mot de passe
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required autocomplete="new-password" placeholder="Confirmez votre mot de passe">
                            </div>

                            <input type="text" name="role" value="auteur" hidden>

                            <div class="form-check mb-3">
                                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" value="1" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="{{ route('cgu') }}" class="auth-link">Conditions d'utilisation</a> et la <a href="{{ route('confidentialite') }}" class="auth-link">Politique de confidentialité</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-3">
                                <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                            </div>

                            <button type="submit" class="btn btn-auth text-white w-100" id="submitBtn">
                                <span id="btnText"><i class="bi bi-person-plus me-2"></i>Créer mon compte</span>
                                <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                    
                    <div class="card-footer text-center" style="background: #f8fafc; border-top: 1px solid #e5e7eb; padding: 1.5rem;">
                        <p class="text-muted mb-0">
                            Déjà un compte ? <a href="{{ route('user.loginForm') }}" class="auth-link">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // alert('Le système d\'inscription est actuellement désactivé pour maintenance. Veuillez réessayer plus tard.');
    // toggle password visibility
    document.getElementById('togglePassword')?.addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = this.querySelector('i');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

    // simple password strength meter
    const pwd = document.getElementById('password');
    const bar = document.getElementById('passwordStrength');
    function scorePassword(p) {
        let score = 0;
        if (!p) return score;
        // length
        if (p.length >= 8) score += 1;
        if (p.length >= 12) score += 1;
        // variety
        if (/[A-Z]/.test(p)) score += 1;
        if (/[a-z]/.test(p)) score += 1;
        if (/[0-9]/.test(p)) score += 1;
        if (/[^A-Za-z0-9]/.test(p)) score += 1;
        return Math.min(score, 5);
    }
    function refreshStrength() {
        const s = scorePassword(pwd.value);
        const widths = ['0%', '20%', '40%', '60%', '80%', '100%'];
        const classes = ['bg-danger','bg-danger','bg-warning','bg-info','bg-primary','bg-success'];
        bar.style.width = widths[s];
        classes.forEach(c => bar.classList.remove(c));
        bar.classList.add(classes[s]);
        bar.setAttribute('aria-valuenow', s * 20);
        bar.setAttribute('aria-valuemin', 0);
        bar.setAttribute('aria-valuemax', 100);
    }
    pwd?.addEventListener('input', refreshStrength);
    refreshStrength();

    // Empêcher la soumission si des champs obligatoires sont vides
    $('#registerForm').on('submit', function(e) {
        let valid = true;

        // Vérifie chaque champ requis
        $('#registerForm [required]').each(function() {
            if (!$(this).val() || ($(this).is(':checkbox') && !$(this).is(':checked'))) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            $('#submitBtn').prop('disabled', false);
            $('#btnText').removeClass('d-none');
            $('#spinner').addClass('d-none');
            return false;
        }

        // Spinner lors de la soumission
        $('#submitBtn').prop('disabled', true);
        $('#btnText').addClass('d-none');
        $('#spinner').removeClass('d-none');
    });

    // Si la page contient des erreurs, on réinitialise le bouton et le spinner
    @if ($errors->any())
        $('#submitBtn').prop('disabled', false);
        $('#btnText').removeClass('d-none');
        $('#spinner').addClass('d-none');
    @endif
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
