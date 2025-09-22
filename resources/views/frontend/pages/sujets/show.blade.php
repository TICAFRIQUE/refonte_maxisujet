<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\sujets\show.blade.php -->
@extends('frontend.layouts.front_app')
@section('title', 'Sujet de ' . $sujet->matiere->libelle . ' - ' . $sujet->titre . ' | MaxiSujets')
@section('meta_description', 'Téléchargez le sujet de ' . $sujet->matiere->libelle . ' : ' . $sujet->titre . '. Document éducatif gratuit  avec corrigé disponible.')
@section('meta_keywords', $sujet->matiere->libelle . ',  sujet gratuit, exercice corrigé, téléchargement, ' . $sujet->titre)
@section('og_title', $sujet->titre . ' - Sujet de ' . $sujet->matiere->libelle)
@section('og_description', 'Téléchargez gratuitement ce sujet de ' . $sujet->matiere->libelle . ' avec corrigé détaillé.')
@section('og_image', $sujet->getFirstMediaUrl('non_corrige') ?: asset('frontend/img/logo.png'))

@section('content')
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4 my-5">
        <ol class="breadcrumb bg-white rounded shadow-sm p-4">
            <li class="breadcrumb-item">
                <a href="{{ route('accueil') }}" class="text-primary text-decoration-none">
                    <i class="bi bi-house-door"></i> Accueil
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('sujet.front.index') }}" class="text-primary text-decoration-none">
                    Sujets
                </a>
            </li>
            @if($sujet->categorie)
                <li class="breadcrumb-item">
                    <a href="{{ route('sujet.front.index', ['categorie' => $sujet->categorie->slug]) }}" class="text-primary text-decoration-none">
                        {{ $sujet->categorie->libelle }}
                    </a>
                </li>
            @endif
            @foreach($sujet->niveaux as $niveau)
                <li class="breadcrumb-item">
                    <a href="{{ route('sujet.front.index', ['niveau' => $niveau->slug]) }}" class="text-primary text-decoration-none">
                        {{ $niveau->libelle }}
                    </a>
                </li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">
                {{ $sujet->libelle }}
            </li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-primary">{{ $sujet->libelle }} 
                        <span class="badge bg-dark">{{ $sujet->code }}</span>
                    </h3>
                    <p class="mb-2">{{ $sujet->description }}</p>
                    <div class="mb-3">
                        <span class="badge bg-info">{{ $sujet->matiere->libelle ?? '' }}</span>
                        @foreach($sujet->niveaux as $niveau)
                            <span class="badge bg-secondary">{{ $niveau->libelle }}</span>
                        @endforeach
                        <span class="badge bg-warning text-dark">{{ $sujet->annee }}</span>
                        <span class="badge bg-success">{{ $sujet->categorie->libelle ?? '' }}</span>
                    </div>
                    <div class="mb-3 text-muted small">
                        <i class="bi bi-calendar"></i>
                        Publié le {{ $sujet->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="bi bi-file-earmark-text"></i> Aperçu du sujet</h6>
                    @auth
                        @if(auth()->user()->points > 0 && $sujet->getFirstMediaUrl('non_corrige'))
                            @php
                                $fileUrl = $sujet->getFirstMediaUrl('non_corrige');
                                $isPdf = $fileUrl && Str::endsWith($fileUrl, '.pdf');
                            @endphp
                            <div class="text-center mb-3">
                                @if($isPdf)
                                    <img src="{{ asset('frontend/img/pdf-icon.png') }}" alt="PDF"
                                         class="img-fluid" style="max-height:120px;">
                                @else
                                    <img src="{{ $fileUrl }}" alt="Aperçu"
                                         class="img-fluid rounded" style="max-height:120px; object-fit:cover; border:1px solid #eee;">
                                @endif
                            </div>
                        @else
                            <div class="alert alert-info w-100 mb-3">
                                @if(auth()->user()->points <= 0)
                                    Points insuffisants pour voir l'aperçu du sujet.
                                @else
                                    Aucun aperçu du sujet disponible.
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning w-100 mb-3">
                            Connectez-vous pour voir l'aperçu du sujet.
                        </div>
                    @endauth

                    <h6 class="text-success mb-3"><i class="bi bi-file-earmark-check"></i> Aperçu du corrigé</h6>
                    @auth
                        @if(auth()->user()->points > 0 && $sujet->getFirstMediaUrl('corrige'))
                            @php
                                $corrigeUrl = $sujet->getFirstMediaUrl('corrige');
                                $isPdfCorrige = $corrigeUrl && Str::endsWith($corrigeUrl, '.pdf');
                            @endphp
                            <div class="text-center mb-3">
                                @if($isPdfCorrige)
                                    <img src="{{ asset('frontend/img/pdf-icon.png') }}" alt="PDF"
                                         class="img-fluid" style="max-height:120px;">
                                @else
                                    <img src="{{ $corrigeUrl }}" alt="Aperçu"
                                         class="img-fluid rounded" style="max-height:120px; object-fit:cover; border:1px solid #eee;">
                                @endif
                            </div>
                        @else
                            <div class="alert alert-info w-100">
                                @if(auth()->user()->points <= 0)
                                    Points insuffisants pour voir l'aperçu du corrigé.
                                @else
                                    Aucun aperçu du corrigé disponible.
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning w-100">
                            Connectez-vous pour voir l'aperçu du corrigé.
                        </div>
                    @endauth
                </div>
                <div class="card-footer bg-white border-0 d-grid gap-2">
                    @auth
                        @if(auth()->user()->points > 0)
                            @if($sujet->getFirstMediaUrl('non_corrige'))
                                <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'non_corrige']) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download"></i> Télécharger le sujet
                                </a>
                            @endif
                            @if($sujet->getFirstMediaUrl('corrige'))
                                <a href="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => 'corrige']) }}" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-download"></i> Télécharger le corrigé
                                </a>
                            @endif
                        @else
                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="bi bi-download"></i> Points insuffisants pour télécharger
                            </button>
                        @endif
                    @else
                        <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-lock"></i> Connectez-vous pour télécharger
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- cycles et niveaux -->
    @include('frontend.components.cycle_niveaux')
</div>
@endsection