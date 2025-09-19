@extends('backend.layouts.master')
@section('title')
    Sujet
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Sujet
        @endslot
        @slot('title')
            Détails Sujet
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informations du sujet</h5>
                    <div>
                        @if($sujet->approuve)
                            <form method="POST" action="{{ route('sujet.approuve', [$sujet->id, 'etat' => 0]) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Désapprouver</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('sujet.approuve', [$sujet->id, 'etat' => 1]) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approuver</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Approuvé</th>
                            <td class="bg-{{ $sujet->approuve ? 'success' : 'danger' }}">
                                {{ $sujet->approuve ? 'Oui' : 'Non' }}</td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>{{ $sujet->statut }}</td>
                        </tr>
                        <tr>
                            <th>Code</th>
                            <td>{{ $sujet->code }}</td>
                        </tr>
                        <tr>
                            <th>Libellé</th>
                            <td>{{ $sujet->libelle }}</td>
                        </tr>

                        <tr>
                            <th>Année</th>
                            <td>{{ $sujet->annee }}</td>
                        </tr>
                        <tr>
                            <th>Catégorie</th>
                            <td>{{ $sujet->categorie ? $sujet->categorie->libelle : '' }}</td>
                        </tr>
                        <tr>
                            <th>Matière</th>
                            <td>{{ $sujet->matiere ? $sujet->matiere->libelle : '' }}</td>
                        </tr>
                       
                        <tr>
                            <th>Niveaux</th>
                            <td>
                                @foreach ($sujet->niveaux as $niveau)
                                    <span class="badge bg-info">{{ $niveau->libelle }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $sujet->description }}</td>
                        </tr>
                         <tr>
                            <th>Auteur</th>
                            <td>{{ $sujet->user ? $sujet->user->username : '' }}</td>
                        </tr>
                        <tr>
                            <th>Date création</th>
                            <td>{{ $sujet->created_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aperçu des fichiers</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Sujet non corrigé</label>
                        @if ($sujet->getFirstMediaUrl('non_corrige'))
                            <iframe src="{{ $sujet->getFirstMediaUrl('non_corrige') }}" width="100%"
                                height="150"></iframe>
                            <div class="mt-2">
                                <a href="{{ $sujet->getFirstMediaUrl('non_corrige') }}" target="_blank"
                                    class="btn btn-primary btn-sm">Voir le sujet</a>
                                <a href="{{ $sujet->getFirstMediaUrl('non_corrige') }}" download
                                    class="btn btn-outline-secondary btn-sm">Télécharger</a>
                            </div>
                        @else
                            <span class="text-muted">Aucun fichier disponible</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet corrigé</label>
                        @if ($sujet->getFirstMediaUrl('corrige'))
                            <iframe src="{{ $sujet->getFirstMediaUrl('corrige') }}" width="100%" height="150"></iframe>
                            <div class="mt-2">
                                <a href="{{ $sujet->getFirstMediaUrl('corrige') }}" target="_blank"
                                    class="btn btn-primary btn-sm">Voir le corrigé</a>
                                <a href="{{ $sujet->getFirstMediaUrl('corrige') }}" download
                                    class="btn btn-outline-secondary btn-sm">Télécharger</a>
                            </div>
                        @else
                            <span class="text-muted">Aucun fichier disponible</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
