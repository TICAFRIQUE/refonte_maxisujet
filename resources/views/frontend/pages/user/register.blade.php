<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\user\register.blade.php -->
@extends('frontend.layouts.front_app')

@section('content')
    <div class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Inscription</h4>
                        <span>Inscrivez-vous gratuitement pour accéder aux ressources de  MaxiSujets.</span>
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

                        <form method="POST" action="{{ route('user.register') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="profil" class="form-label">Profil</label>
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
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" required
                                    value="{{ old('username') }}" placeholder="Ex: johndoe" autocomplete="username">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                    value="{{ old('phone') }}" placeholder="Ex: +2250700000000" autocomplete="tel">
                                <div class="form-text">Inclure l'indicatif international (ex: +33, +221, +225).</div>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" required
                                    value="{{ old('email') }}" placeholder="email@exemple.com" autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" minlength="8" placeholder="Au moins 8 caractères">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="bi bi-eye"></i></button>
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
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required autocomplete="new-password">
                            </div>

                            <input type="text" name="role" value="auteur" hidden>

                            <div class="form-check mb-3">
                                <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" value="1" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les <a href="{{ route('cgu') }}" class="text-decoration-underline">Conditions d'utilisation</a> et la <a href="{{ route('confidentialite') }}" class="text-decoration-underline">Politique de confidentialité</a>.
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-3">
                                <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                            <div class="text-center mt-3">
                                <small class="text-muted">Déjà un compte ? <a href="{{ route('user.loginForm') }}" class="text-decoration-none">Se connecter</a></small>
                            </div>
                        </form>
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
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
