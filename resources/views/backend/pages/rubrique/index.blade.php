@extends('backend.layouts.master')
@section('title')
    Gestion des Rubriques
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Rubriques
        @endslot
        @slot('title')
            Gestion des Rubriques
        @endslot
    @endcomponent
    <div class="container-fluid">
        {{-- <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Rubriques</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Gestion des Rubriques</h4>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <a href="{{ route('backend.rubrique.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Nouvelle Rubrique
                                </a>
                            </div>
                            <div class="col-sm-8">
                                <form method="GET" class="d-flex gap-2">
                                    <select name="type_rubrique" class="form-select">
                                        <option value="">Tous les types</option>
                                        @foreach ($typesRubriques as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ request('type_rubrique') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="statut" class="form-select">
                                        <option value="">Tous les statuts</option>
                                        <option value="publie" {{ request('statut') == 'publie' ? 'selected' : '' }}>Publié
                                        </option>
                                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>
                                            Brouillon</option>
                                    </select>

                                    <input type="text" name="recherche" class="form-control" placeholder="Rechercher..."
                                        value="{{ request('recherche') }}">

                                    <button type="submit" class="btn btn-info">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>

                                    <a href="{{ route('backend.rubrique.index') }}" class="btn btn-secondary">
                                        <i class="mdi mdi-refresh"></i>
                                    </a>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Auteur</th>
                                        <th>Date Publication</th>
                                        <th>Vues</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rubriques as $rubrique)
                                        <tr>
                                            <td>
                                                @if ($rubrique->getFirstMediaUrl('image_principale'))
                                                    <img src="{{ $rubrique->getFirstMediaUrl('image_principale', 'thumb') }}"
                                                        alt="Image" class="img-thumbnail"
                                                        style="width: 50px; height: 50px;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px; border-radius: 4px;">
                                                        <i class="mdi mdi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <h6 class="mb-0">{{ Str::limit($rubrique->titre, 40) }}</h6>
                                                @if ($rubrique->est_featured)
                                                    <span class="badge bg-warning">Mise en avant</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $rubrique->type_rubrique_libelle }}</span>
                                            </td>
                                            <td>
                                                @if ($rubrique->est_publie)
                                                    <span class="badge bg-success">Publié</span>
                                                @else
                                                    <span class="badge bg-secondary">Brouillon</span>
                                                @endif
                                            </td>
                                            <td>{{ $rubrique->auteur->name ?? 'Système' }}</td>
                                            <td>
                                                @if ($rubrique->date_publication)
                                                    {{ $rubrique->date_publication->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="text-muted">Non définie</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $rubrique->nb_vues }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group dropdown">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="{{ route('backend.rubrique.show', $rubrique) }}"
                                                            class="dropdown-item">
                                                            <i class="mdi mdi-eye me-1"></i>Voir
                                                        </a>
                                                        <a href="{{ route('backend.rubrique.edit', $rubrique) }}"
                                                            class="dropdown-item">
                                                            <i class="mdi mdi-pencil me-1"></i>Modifier
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <form
                                                            action="{{ route('backend.rubrique.toggle-statut', $rubrique) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i
                                                                    class="mdi mdi-{{ $rubrique->est_publie ? 'eye-off' : 'eye' }} me-1"></i>
                                                                {{ $rubrique->est_publie ? 'Masquer' : 'Publier' }}
                                                            </button>
                                                        </form>
                                                        <form
                                                            action="{{ route('backend.rubrique.toggle-featured', $rubrique) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i
                                                                    class="mdi mdi-{{ $rubrique->est_featured ? 'star-off' : 'star' }} me-1"></i>
                                                                {{ $rubrique->est_featured ? 'Retirer mise en avant' : 'Mettre en avant' }}
                                                            </button>
                                                        </form>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="{{ route('backend.rubrique.destroy', $rubrique) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette rubrique ?')"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="mdi mdi-delete me-1"></i>Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="mdi mdi-folder-open-outline display-4"></i>
                                                    <p class="mt-2">Aucune rubrique trouvée</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($rubriques->hasPages())
                            <div class="mt-3">
                                {{ $rubriques->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
