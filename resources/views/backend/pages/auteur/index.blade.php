@extends('backend.layouts.master')
@section('title')
    Auteurs
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Auteurs
        @endslot
        @slot('title')
            Auteurs
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            @include('backend.components.alertMessage')
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Auteurs / contributeurs</h5>
                    <small class="text-muted">Comptes inscrits publiquement sur le site — distincts de l'équipe admin.</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Email</th>
                                    <th>Profil</th>
                                    <th>Points</th>
                                    <th>Sujets publiés</th>
                                    <th>Statut</th>
                                    <th>Inscrit le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($auteurs as $key => $item)
                                    <tr id="row_{{ $item->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ ucfirst($item->profil ?? '—') }}</td>
                                        <td><span class="badge bg-warning-subtle text-warning">{{ $item->points ?? 0 }}</span></td>
                                        <td>
                                            {{ $item->sujets_count }} total
                                            <span class="text-muted small">({{ $item->sujets_approuves_count }} approuvés)</span>
                                        </td>
                                        <td>
                                            @if ($item->statut === 'active')
                                                <span class="badge bg-success-subtle text-success">Actif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Désactivé</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at?->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('auteur.show', $item->id) }}" class="btn btn-soft-primary btn-sm" title="Voir les détails">
                                                    <i class="ri-eye-line align-bottom"></i> Détails
                                                </a>
                                                <button type="button" class="btn btn-sm {{ $item->statut === 'active' ? 'btn-soft-danger' : 'btn-soft-success' }} toggle-statut-btn"
                                                    data-id="{{ $item->id }}">
                                                    @if ($item->statut === 'active')
                                                        <i class="ri-forbid-line align-bottom"></i> Désactiver
                                                    @else
                                                        <i class="ri-check-line align-bottom"></i> Activer
                                                    @endif
                                                </button>
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
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        new DataTable('#buttons-datatables');

        $(document).ready(function() {
            $('.toggle-statut-btn').on('click', function(e) {
                e.preventDefault();
                const btn = $(this);
                const id = btn.data('id');

                Swal.fire({
                    title: 'Confirmer ?',
                    text: "Le statut du compte va changer.",
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
                        $.ajax({
                            type: "GET",
                            url: "{{ url('admin/auteur/toggle-statut') }}/" + id,
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {
                                    location.reload();
                                } else {
                                    Swal.fire('Erreur', "Une erreur est survenue.", 'error');
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
