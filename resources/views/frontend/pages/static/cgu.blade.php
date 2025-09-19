@extends('frontend.layouts.front_app')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white rounded shadow-sm px-3 py-2">
            <li class="breadcrumb-item"><a href="{{ route('accueil') }}" class="text-primary text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Conditions d'utilisation</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white"><h5 class="mb-0">Conditions d'utilisation</h5></div>
        <div class="card-body">
            <p>Ces Conditions d'utilisation encadrent l'accès et l'utilisation de la plateforme MaxiSujets. En utilisant le site, vous acceptez ces conditions.</p>
            <h6>1. Compte utilisateur</h6>
            <p>Vous êtes responsable de la confidentialité de vos identifiants et des activités effectuées sous votre compte.</p>
            <h6>2. Contenu</h6>
            <p>Le contenu mis à disposition est destiné à un usage pédagogique. Toute utilisation commerciale non autorisée est interdite.</p>
            <h6>3. Respect de la loi</h6>
            <p>Vous vous engagez à ne pas utiliser la plateforme à des fins illicites.</p>
        </div>
    </div>
</div>
@endsection