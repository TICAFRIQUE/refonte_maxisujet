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
        <div class="col-lg-9">
            <form method="GET" action="{{ route('sujet.index') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label for="approuve" class="form-label mb-0">Approuvé</label>
                    <select name="approuve" id="approuve" class="form-select">
                        <option value="">Tous</option>
                        <option value="1" {{ request('approuve') === '1' ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ request('approuve') === '0' ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="categorie_id" class="form-label mb-0">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string) request('categorie_id') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="matiere_id" class="form-label mb-0">Matière</label>
                    <select name="matiere_id" id="matiere_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach ($matieres as $mat)
                            <option value="{{ $mat->id }}" {{ (string) request('matiere_id') === (string) $mat->id ? 'selected' : '' }}>{{ $mat->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="concours_id" class="form-label mb-0">Concours</label>
                    <select name="concours_id" id="concours_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach ($concoursList as $conc)
                            <option value="{{ $conc->id }}" {{ (string) request('concours_id') === (string) $conc->id ? 'selected' : '' }}>{{ $conc->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_debut" class="form-label mb-0">Date début</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_fin" class="form-label mb-0">Date fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-3">
                    <label for="code" class="form-label mb-0">Code</label>
                    <input type="text" name="code" id="code" class="form-control" placeholder="Ex: MS4F7B2" value="{{ request('code') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line"></i> Filtrer</button>
                </div>
                @if (request()->anyFilled(['approuve', 'categorie_id', 'matiere_id', 'concours_id', 'code', 'date_debut', 'date_fin']))
                    <div class="col-md-2">
                        <a href="{{ route('sujet.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
                    </div>
                @endif
            </form>
        </div>
        <div class="col-lg-3 text-end">
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
                                    <th>Libellé</th>
                                    <th>Catégorie</th>
                                    <th>Matière</th>
                                    <th>Auteur</th>
                                    <th>Téléch.</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sujets as $key => $item)
                                    <tr id="row_{{ $item->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td> <span class="badge {{ $item->approuve == 1 ? 'bg-success' : 'bg-danger' }}">{{ $item->approuve == 1 ? 'Oui' : 'Non' }}</span></td>
                                        <td>{{ $item->code }}</td>
                                        <td>
                                            <a href="{{ route('sujet.show', $item->id) }}">{{ Str::limit($item->libelle, 35) }}</a>
                                        </td>
                                        <td>{{ $item->categorie ? $item->categorie->libelle : '' }}</td>
                                        <td>{{ $item->matiere ? $item->matiere->libelle : '' }}</td>
                                        <td>{{ $item->user ? $item->user->username : '' }}</td>
                                        <td><span class="badge bg-primary-subtle text-primary">{{ $item->downloads_count }}</span></td>
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                @if ($item->approuve == 1)
                                                    <form action="{{ route('sujet.approuve', ['id' => $item->id, 'etat' => 0]) }}" method="POST" class="d-inline approuve-form"
                                                        data-message="Retirer l'approbation de « {{ $item->libelle }} » ? L'auteur perdra les points gagnés pour ce sujet.">
                                                        @csrf
                                                        <button type="submit" class="btn btn-soft-warning btn-sm" title="Retirer l'approbation">
                                                            <i class="ri-close-circle-line align-bottom"></i> Retirer
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('sujet.approuve', ['id' => $item->id, 'etat' => 1]) }}" method="POST" class="d-inline approuve-form"
                                                        data-message="Approuver « {{ $item->libelle }} » ? Le sujet sera publié et son auteur gagnera des points.">
                                                        @csrf
                                                        <button type="submit" class="btn btn-soft-success btn-sm" title="Approuver ce sujet">
                                                            <i class="ri-check-line align-bottom"></i> Approuver
                                                        </button>
                                                    </form>
                                                @endif
                                                <!-- Actions (voir/modifier/supprimer) -->
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

            $('.approuve-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Confirmer ?',
                    text: $(form).data('message'),
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmer',
                    cancelButtonText: 'Annuler',
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                        cancelButton: 'btn btn-secondary w-xs mt-2',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        })
    </script>
@endsection
