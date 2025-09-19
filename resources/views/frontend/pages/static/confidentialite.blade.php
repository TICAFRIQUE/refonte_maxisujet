@extends('frontend.layouts.front_app')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
            <li class="breadcrumb-item"><a href="{{ route('accueil') }}" class="text-primary text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Politique de confidentialité</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white"><h5 class="mb-0">Politique de confidentialité</h5></div>
        <div class="card-body">
            <p>Nous respectons votre vie privée. Cette politique décrit les données collectées et l'usage qui en est fait.</p>
            <h6>Données collectées</h6>
            <p>Informations de compte (email, nom d'utilisateur), logs techniques, etc.</p>
            <h6>Utilisation</h6>
            <p>Amélioration du service, communication avec les utilisateurs, sécurité.</p>
        </div>
    </div>
</div>
@endsection