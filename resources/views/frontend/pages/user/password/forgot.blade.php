@extends('frontend.layouts.front_app')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded shadow-sm p-4">
            <li class="breadcrumb-item"><a href="{{ route('accueil') }}" class="text-primary text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mot de passe oublié</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Mot de passe oublié</h4>
                    <span>Entrez votre email pour recevoir un lien de réinitialisation.</span>
                </div>
                <div class="card-body">
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
                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                            <span id="btnText">Envoyer le lien</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
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