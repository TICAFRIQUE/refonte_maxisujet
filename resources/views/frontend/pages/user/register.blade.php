<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\user\register.blade.php -->
@extends('frontend.layouts.front_app')

@section('content')
<style>
    .auth-card {
        border: 1px solid var(--ms-border-subtle);
        border-radius: var(--ms-radius-lg);
        box-shadow: var(--ms-shadow-rest);
        overflow: hidden;
    }

    .auth-header {
        background: var(--ms-orange) !important;
        padding: 1.75rem 1.5rem;
    }

    .auth-body {
        padding: 2rem 1.5rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ms-orange);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
    }

    .btn-auth {
        background: var(--ms-orange);
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
    }

    .btn-auth:hover {
        background: var(--ms-orange-dark);
    }

    .auth-link {
        color: var(--ms-blue);
        text-decoration: none;
        font-weight: 600;
    }

    .auth-link:hover {
        color: var(--ms-blue-dark);
        text-decoration: underline;
    }

    .password-toggle:hover {
        background-color: var(--ms-orange);
        color: white;
        border-color: var(--ms-orange);
    }

    .form-check-input:checked {
        background-color: var(--ms-orange);
        border-color: var(--ms-orange);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.15);
    }

    .password-match-feedback {
        font-size: 0.85rem;
        margin-top: 0.35rem;
    }
</style>
    <div class="container py-5 min-vh-100 d-flex flex-column">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            @include('frontend.components.retour')
        <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
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
        </div>
        <div class="row justify-content-center flex-grow-1">
            <div class="col-md-6 d-flex align-items-center">
                <div class="card auth-card">
                    <div class="card-header auth-header text-white text-center">
                        <h4 class="mb-2">Rejoignez MaxiSujets</h4>
                        <p class="mb-3 opacity-90">Inscrivez-vous gratuitement pour accéder aux ressources et publier des sujets</p>
                        <span class="badge bg-white px-3 py-2" style="color: var(--ms-orange-dark); font-size: 0.9rem;">
                            <i class="bi bi-star-fill me-1"></i>+50 points offerts à l'inscription
                        </span>
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
                                    value="{{ old('phone', '+225 ') }}" placeholder="+225 07 00 00 00 00" autocomplete="tel">
                                <div class="form-text">Hors de Côte d'Ivoire ? Remplacez le préfixe par le vôtre (ex: +33, +221).</div>
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
                                <div class="password-match-feedback" id="passwordMatchFeedback"></div>
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
                    
                    <div class="card-footer text-center" style="background: var(--ms-bg-soft); border-top: 1px solid var(--ms-border-subtle); padding: 1.5rem;">
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

    // Retour en temps réel sur la correspondance des mots de passe
    const pwdConfirm = document.getElementById('password_confirmation');
    const matchFeedback = document.getElementById('passwordMatchFeedback');
    function refreshMatch() {
        if (!pwdConfirm.value) {
            matchFeedback.textContent = '';
            return;
        }
        if (pwd.value === pwdConfirm.value) {
            matchFeedback.textContent = 'Les mots de passe correspondent';
            matchFeedback.className = 'password-match-feedback text-success';
        } else {
            matchFeedback.textContent = 'Les mots de passe ne correspondent pas';
            matchFeedback.className = 'password-match-feedback text-danger';
        }
    }
    pwd?.addEventListener('input', refreshMatch);
    pwdConfirm?.addEventListener('input', refreshMatch);

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
