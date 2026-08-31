@extends('backend.layouts.master')
@section('title')
    {{ $auteur->username }}
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('auteur.index') }}">Auteurs</a>
        @endslot
        @slot('title')
            {{ $auteur->username }}
        @endslot
    @endcomponent

    @include('backend.components.alertMessage')

    <div class="row">
        <!-- Profil -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle" style="width:96px;height:96px;font-size:2.5rem;display:flex;align-items:center;justify-content:center;margin:auto;">
                            {{ strtoupper(substr($auteur->username, 0, 1)) }}
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $auteur->username }}</h4>
                    <p class="text-muted mb-3">{{ ucfirst($auteur->profil ?? '—') }}</p>

                    @if ($auteur->statut === 'active')
                        <span class="badge bg-success-subtle text-success mb-3">Compte actif</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger mb-3">Compte désactivé</span>
                    @endif

                    <ul class="list-group list-group-flush text-start mt-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="ri-mail-line me-1"></i> Email</span>
                            <span>{{ $auteur->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="ri-phone-line me-1"></i> Téléphone</span>
                            <span>{{ $auteur->phone ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="ri-star-fill me-1"></i> Points</span>
                            <span class="fw-bold text-warning">{{ $auteur->points ?? 0 }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="ri-file-list-2-line me-1"></i> Sujets publiés</span>
                            <span>{{ $sujets->count() }} ({{ $sujets->where('approuve', 1)->count() }} approuvés)</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="ri-download-2-line me-1"></i> Téléchargements effectués</span>
                            <span>{{ $downloads->total() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="ri-calendar-line me-1"></i> Inscrit le</span>
                            <span>{{ $auteur->created_at?->format('d/m/Y') }}</span>
                        </li>
                    </ul>

                    <button type="button" class="btn {{ $auteur->statut === 'active' ? 'btn-danger' : 'btn-success' }} w-100 mt-3 toggle-statut-btn"
                        data-id="{{ $auteur->id }}">
                        @if ($auteur->statut === 'active')
                            <i class="ri-forbid-line align-bottom"></i> Désactiver ce compte
                        @else
                            <i class="ri-check-line align-bottom"></i> Activer ce compte
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Sujets publiés + téléchargements -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="ri-file-list-2-line"></i> Sujets publiés
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Libellé</th>
                                    <th>Catégorie</th>
                                    <th>Matière</th>
                                    <th>Statut</th>
                                    <th>Téléch.</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sujets as $sujet)
                                    <tr>
                                        <td>{{ $sujet->code }}</td>
                                        <td>{{ $sujet->libelle }}</td>
                                        <td>{{ $sujet->categorie->libelle ?? '—' }}</td>
                                        <td>{{ $sujet->matiere->libelle ?? '—' }}</td>
                                        <td>
                                            @if ($sujet->approuve)
                                                <span class="badge bg-success-subtle text-success">Approuvé</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">En attente</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-primary-subtle text-primary">{{ $sujet->downloads_count }}</span></td>
                                        <td>{{ $sujet->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('sujet.show', $sujet->id) }}" class="btn btn-soft-secondary btn-sm">
                                                <i class="ri-eye-line align-bottom"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-3">Aucun sujet publié pour le moment.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <i class="ri-download-2-line"></i> Historique des téléchargements
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Sujet</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($downloads as $download)
                                    <tr>
                                        <td>{{ $download->sujet->libelle ?? 'Sujet supprimé' }}</td>
                                        <td>{{ $download->type === 'corrige' ? 'Corrigé' : 'Sujet' }}</td>
                                        <td>{{ $download->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Aucun téléchargement pour le moment.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($downloads->hasPages())
                        <div class="p-3">
                            {{ $downloads->links() }}
                        </div>
                    @endif
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
            $('.toggle-statut-btn').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

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
