@extends('backend.layouts.master')
@section('title')
    {{-- @lang('translation.datatables') --}}
    Sujet
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
            Sujet
        @endslot
        @slot('title')
            Sujet
        @endslot
    @endcomponent

    <div class="row mb-3">
        <div class="col-lg-8">
            <form method="GET" action="{{ route('sujet.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="approuve" class="form-label mb-0">Approuvé</label>
                    <select name="approuve" id="approuve" class="form-select">
                        <option value="">Tous</option>
                        <option value="1" {{ request('approuve') === '1' ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ request('approuve') === '0' ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_debut" class="form-label mb-0">Date début</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_fin" class="form-label mb-0">Date fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line"></i> Filtrer</button>
                </div>
            </form>
        </div>
        <div class="col-lg-4 text-end">
            <div class="alert alert-warning py-2 px-3 mb-0 d-inline-block">
                <i class="ri-error-warning-line"></i>
                <strong>{{ $sujetsNonApprouves ?? 0 }}</strong> sujet(s) non approuvé(s)
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Liste des sujets</h5>
                    <a href="{{ route('sujet.create') }}" class="btn btn-primary ">Créer un sujet</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Approuvé</th>
                                    <th>Code</th>
                                    <th>Libelle</th>
                                    <th>Description</th>
                                    <th>Statut</th>
                                    <th>Année</th>
                                    <th>Catégorie</th>
                                    <th>Matière</th>
                                    <th>Auteur</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sujets as $key => $item)
                                    <tr id="row_{{ $item->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td> <span class="badge {{ $item->approuve == 1 ? 'bg-success' : 'bg-danger' }}">{{ $item->approuve == 1 ? 'Oui' : 'Non' }}</span></td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->libelle }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->statut }}</td>
                                        <td>{{ $item->annee }}</td>
                                        <td>{{ $item->categorie ? $item->categorie->libelle : '' }}</td>
                                        <td>{{ $item->matiere ? $item->matiere->libelle : '' }}</td>
                                        <td>{{ $item->user ? $item->user->username : '' }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <!-- Actions (modifier/supprimer) -->
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a href="{{ route('sujet.show', $item->id) }}" type="button"
                                                            class="dropdown-item edit-item-btn"><i
                                                                class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                            Details</a></li>
                                                    <li><a href="{{ route('sujet.edit', $item->id) }}" type="button"
                                                            class="dropdown-item edit-item-btn"><i
                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                            Modifier</a></li>
                                                    <li>
                                                        <a href="#" class="dropdown-item remove-item-btn delete"
                                                            data-id={{ $item->id }}>
                                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                            Supprimer
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end row-->
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            var route = "sujet"
            delete_row(route);
        })
    </script>
@endsection
