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
<div class="container my-5">
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        @include('frontend.components.retour')
    <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
        <ol class="breadcrumb bg-light rounded p-3">
            <li class="breadcrumb-item"><a href="{{ route('accueil') }}"><i class="bi bi-house-door"></i> Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Réinitialiser le mot de passe</li>
        </ol>
    </nav>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card">
                <div class="card-header auth-header text-white text-center">
                    <h4 class="mb-0">Réinitialiser le mot de passe</h4>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@exemple.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password" minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-check-circle me-2"></i>Réinitialiser le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
