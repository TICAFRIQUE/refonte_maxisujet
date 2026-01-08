@extends('backend.layouts.master')

@section('title', 'Détail Rubrique')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.rubrique.index') }}">Rubriques</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($rubrique->titre, 30) }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Détail Rubrique</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @if($rubrique->getFirstMediaUrl('image_principale'))
                        <div class="mb-4">
                            <img src="{{ $rubrique->getFirstMediaUrl('image_principale') }}" 
                                 alt="{{ $rubrique->titre }}" class="img-fluid rounded">
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="h3">{{ $rubrique->titre }}</h1>
                        <div class="d-flex gap-2">
                            @if($rubrique->est_publie)
                                <span class="badge bg-success">Publié</span>
                            @else
                                <span class="badge bg-secondary">Brouillon</span>
                            @endif
                            @if($rubrique->est_featured)
                                <span class="badge bg-warning">En avant</span>
                            @endif
                            <span class="badge bg-info">{{ $rubrique->type_rubrique_libelle }}</span>
                        </div>
                    </div>

                    @if($rubrique->resume)
                        <div class="alert alert-light border-start border-4 border-primary">
                            <strong>Résumé:</strong> {{ $rubrique->resume }}
                        </div>
                    @endif

                    <div class="content">
                        {!! nl2br(e($rubrique->contenu)) !!}
                    </div>

                    @if($rubrique->tags && count($rubrique->tags) > 0)
                        <div class="mt-4">
                            <h6>Tags:</h6>
                            <div>
                                @foreach($rubrique->tags as $tag)
                                    <span class="badge bg-secondary me-1">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-muted">Slug:</div>
                            <div class="fw-semibold">{{ $rubrique->slug }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted">Type:</div>
                            <div class="fw-semibold">{{ $rubrique->type_rubrique_libelle }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-muted">Auteur:</div>
                            <div class="fw-semibold">{{ $rubrique->auteur->name ?? 'Système' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted">Vues:</div>
                            <div class="fw-semibold">{{ $rubrique->nb_vues }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="text-muted">Date de publication:</div>
                            <div class="fw-semibold">
                                @if($rubrique->date_publication)
                                    {{ $rubrique->date_publication->format('d/m/Y à H:i') }}
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-muted">Créé le:</div>
                            <div class="fw-semibold">{{ $rubrique->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted">Modifié le:</div>
                            <div class="fw-semibold">{{ $rubrique->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="text-muted">Ordre d'affichage:</div>
                            <div class="fw-semibold">{{ $rubrique->ordre_affichage }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('backend.rubrique.edit', $rubrique) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil"></i> Modifier
                        </a>
                        
                        <form action="{{ route('backend.rubrique.toggle-statut', $rubrique) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-{{ $rubrique->est_publie ? 'warning' : 'success' }}">
                                <i class="mdi mdi-{{ $rubrique->est_publie ? 'eye-off' : 'eye' }}"></i>
                                {{ $rubrique->est_publie ? 'Masquer' : 'Publier' }}
                            </button>
                        </form>

                        <form action="{{ route('backend.rubrique.toggle-featured', $rubrique) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-{{ $rubrique->est_featured ? 'outline-warning' : 'warning' }}">
                                <i class="mdi mdi-{{ $rubrique->est_featured ? 'star-off' : 'star' }}"></i>
                                {{ $rubrique->est_featured ? 'Retirer mise en avant' : 'Mettre en avant' }}
                            </button>
                        </form>

                        <hr>

                        <form action="{{ route('backend.rubrique.destroy', $rubrique) }}" method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette rubrique ?')" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="mdi mdi-delete"></i> Supprimer
                            </button>
                        </form>

                        <a href="{{ route('backend.rubrique.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection