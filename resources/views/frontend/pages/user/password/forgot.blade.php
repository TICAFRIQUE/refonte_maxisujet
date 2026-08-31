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
        background: var(--ms-orange);
        padding: 1.75rem 1.5rem;
    }
</style>
<div class="container mb-5">
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        @include('frontend.components.retour')
    <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
        <ol class="breadcrumb bg-light rounded p-3">
            <li class="breadcrumb-item"><a href="{{ route('accueil') }}"><i class="bi bi-house-door"></i> Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mot de passe oublié</li>
        </ol>
    </nav>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card">
                <div class="card-header auth-header text-white text-center">
                    <h4 class="mb-2">Mot de passe oublié</h4>
                    <p class="mb-0 opacity-90">Entrez votre email pour recevoir un lien de réinitialisation</p>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate id="forgotForm">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@exemple.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-warning w-100" id="submitBtn">
                            <span id="btnText"><i class="bi bi-send me-2"></i>Envoyer le lien</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
                <div class="card-footer text-center" style="background: var(--ms-bg-soft); border-top: 1px solid var(--ms-border-subtle);">
                    <p class="text-muted mb-0">
                        <a href="{{ route('user.loginForm') }}" class="text-decoration-none fw-semibold" style="color: var(--ms-blue);">
                            <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#forgotForm').on('submit', function(e) {
        var email = $('#email').val().trim();
        if(email === '') {
            e.preventDefault();
            $('#email').addClass('is-invalid');
            $('#submitBtn').prop('disabled', false);
            $('#btnText').removeClass('d-none');
            $('#spinner').addClass('d-none');
            return false;
        } else {
            $('#email').removeClass('is-invalid');
            $('#submitBtn').prop('disabled', true);
            $('#btnText').addClass('d-none');
            $('#spinner').removeClass('d-none');
        }
    });
</script>
@endpush
