<!-- filepath: c:\laragon\www\refonte_maxisujet\resources\views\frontend\pages\user\sujet\index.blade.php -->
@extends('frontend.layouts.front_app')

@section('content')
    <div class="container my-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded shadow-sm p-4">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"
                        class="text-primary text-decoration-none"><i class="bi bi-person-circle"></i> Mon espace</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mes sujets publiés</li>
            </ol>
        </nav>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mes sujets publiés</h5>
                <a href="{{ route('user.sujet.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-lg"></i> Publier un sujet
                </a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($sujets->isEmpty())
                    <div class="alert alert-info">Vous n'avez publié aucun sujet pour le moment.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Titre</th>
                                    <th>Code</th>
                                    <th>Catégorie</th>
                                    <th>Approuvé</th>
                                    <th>Publié le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sujets as $sujet)
                                    <tr>
                                        <td>{{ $sujet->libelle }}</td>
                                        <td>{{ $sujet->code }}</td>
                                        <td>{{ $sujet->categorie->libelle ?? '-' }}</td>

                                        <td>
                                            <span class="badge {{ $sujet->approuve == 1 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $sujet->statut == 1 ? 'Oui' : 'Non' }}
                                            </span>
                                        </td>
                                        <td>{{ $sujet->created_at->format('d/m/Y à H:i') }}</td>
                                        <td>
                                            <a href="{{ route('sujet.front.show', $sujet->libelle) }}"
                                                class="btn btn-outline-primary btn-sm" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('user.sujet.edit', $sujet->id) }}"
                                                class="btn btn-outline-warning btn-sm" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('user.sujet.delete', $sujet->id) }}"
                                                data-id="{{ $sujet->id }}" class="btn btn-outline-danger btn-sm delete"
                                                title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $sujets->links() }}
                @endif
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                $('.delete').on("click", function(e) {
                    e.preventDefault();
                    var Id = $(this).attr('data-id');
                    var url = '/user/sujet/delete/'
                    Swal.fire({
                        title: 'Etes-vous sûr(e) de vouloir supprimer ?',
                        text: "Cette action est irréversible!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Supprimer!',
                        cancelButtonText: 'Annuler',
                        customClass: {
                            confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                            cancelButton: 'btn btn-danger w-xs mt-2',
                        },
                        buttonsStyling: false,
                        showCloseButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: url + Id,
                                dataType: "json",
                                // data: {
                                //     _token: '{{ csrf_token() }}',

                                // },
                                success: function(response) {
                                    if (response.status == 200) {
                                        Swal.fire({
                                            title: 'Supprimé!',
                                            text: 'Suppression effectuée avec succès.',
                                            icon: 'success',
                                            customClass: {
                                                confirmButton: 'btn btn-primary w-xs mt-2',
                                            },
                                            buttonsStyling: false
                                        })

                                        location.reload();
                                    }
                                }
                            });
                        }
                    });
                });
            })
        </script>
    @endpush
@endsection
