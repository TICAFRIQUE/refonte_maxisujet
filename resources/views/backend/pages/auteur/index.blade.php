@extends('backend.layouts.master')
@section('title')
    Auteurs
@endsection
@section('css')
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

    <div class="row mb-3">
        <div class="col-xl-2 col-md-4">
            <div class="card card-animate mb-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total auteurs</p>
                            <h4 class="fs-22 fw-semibold mb-0 mt-1">{{ $kpiAuteurs['total'] }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title rounded-circle fs-3 bg-primary-subtle text-primary">
                                <i class="ri-team-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card card-animate mb-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Inscrits aujourd'hui</p>
                            <h4 class="fs-22 fw-semibold mb-0 mt-1">{{ $kpiAuteurs['inscrits_aujourdhui'] }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title rounded-circle fs-3 bg-info-subtle text-info">
                                <i class="ri-calendar-event-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card card-animate mb-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Actifs</p>
                            <h4 class="fs-22 fw-semibold mb-0 mt-1">{{ $kpiAuteurs['actifs'] }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title rounded-circle fs-3 bg-success-subtle text-success">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card card-animate mb-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Désactivés</p>
                            <h4 class="fs-22 fw-semibold mb-0 mt-1">{{ $kpiAuteurs['desactives'] }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title rounded-circle fs-3 bg-danger-subtle text-danger">
                                <i class="ri-forbid-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card card-animate mb-2">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Sujets publiés</p>
                            <h4 class="fs-22 fw-semibold mb-0 mt-1">{{ $kpiAuteurs['sujets_publies'] }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title rounded-circle fs-3 bg-secondary-subtle text-secondary">
                                <i class="ri-file-text-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            @include('backend.components.alertMessage')
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Auteurs / contributeurs</h5>
                    <small class="text-muted">Comptes inscrits publiquement sur le site — distincts de l'équipe admin.</small>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('auteur.index') }}" class="row g-2 align-items-end mb-3">
                        <div class="col-md-4">
                            <label for="q" class="form-label mb-0">Recherche</label>
                            <input type="text" name="q" id="q" class="form-control" placeholder="Nom d'utilisateur ou email" value="{{ request('q') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="statut" class="form-label mb-0">Statut</label>
                            <select name="statut" id="statut" class="form-select">
                                <option value="">Tous</option>
                                <option value="active" {{ request('statut') === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="desactive" {{ request('statut') === 'desactive' ? 'selected' : '' }}>Désactivé</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="ri-search-line"></i> Filtrer</button>
                        </div>
                        @if (request()->anyFilled(['q', 'statut']))
                            <div class="col-md-2">
                                <a href="{{ route('auteur.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
                            </div>
                        @endif
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered" style="width:100%">
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
                                        <td>{{ $auteurs->firstItem() + $key }}</td>
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

                    <div class="mt-3">
                        {{ $auteurs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.toggle-statut-btn', function(e) {
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
