@extends('backend.layouts.master')
@section('title')
    {{ $sujet->libelle }}
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('sujet.index') }}">Sujets</a>
        @endslot
        @slot('title')
            {{ $sujet->libelle }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informations du sujet</h5>
                    <div>
                        @if($sujet->approuve)
                            <form method="POST" action="{{ route('sujet.approuve', [$sujet->id, 'etat' => 0]) }}" class="d-inline approuve-form"
                                data-message="Retirer l'approbation de ce sujet ? L'auteur perdra les points gagnés pour ce sujet.">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Désapprouver</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('sujet.approuve', [$sujet->id, 'etat' => 1]) }}" class="d-inline approuve-form"
                                data-message="Approuver ce sujet ? Il sera publié et son auteur gagnera des points.">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approuver</button>
                            </form>
                        @endif
                        <a href="{{ route('sujet.edit', $sujet->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ri-pencil-fill align-bottom"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 220px;">Approuvé</th>
                            <td>
                                @if ($sujet->approuve)
                                    <span class="badge bg-success-subtle text-success">Oui</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">En attente</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if ($sujet->statut === 'active')
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Désactivé</span>
                                @endif
                            </td>
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
                            <td>{{ $sujet->categorie->libelle ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Matière</th>
                            <td>{{ $sujet->matiere->libelle ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Concours</th>
                            <td>{{ $sujet->concours->libelle ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Niveaux</th>
                            <td>
                                @forelse ($sujet->niveaux as $niveau)
                                    <span class="badge bg-info-subtle text-info me-1">{{ $niveau->libelle }}</span>
                                @empty
                                    —
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $sujet->description ?: '—' }}</td>
                        </tr>
                        <tr>
                            <th>Auteur</th>
                            <td>
                                @if ($sujet->user)
                                    <a href="{{ route('auteur.show', $sujet->user->id) }}">{{ $sujet->user->username }}</a>
                                    <span class="text-muted small">({{ $sujet->user->email }})</span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date création</th>
                            <td>{{ $sujet->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Dernière modification</th>
                            <td>{{ $sujet->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Téléchargements</th>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">{{ $sujet->downloads_count }} au total</span>
                                <span class="badge bg-secondary-subtle text-secondary">{{ $sujet->downloads_non_corrige_count }} sujet</span>
                                <span class="badge bg-success-subtle text-success">{{ $sujet->downloads_corrige_count }} corrigé</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <i class="ri-download-2-line"></i> Derniers téléchargements
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Auteur</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($derniersTelechargements as $dl)
                                    <tr>
                                        <td>{{ $dl->user->username ?? '—' }}</td>
                                        <td>{{ $dl->type === 'corrige' ? 'Corrigé' : 'Sujet' }}</td>
                                        <td>{{ $dl->created_at->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Aucun téléchargement pour le moment.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                        @php $mediaNonCorrige = $sujet->getFirstMedia('non_corrige'); @endphp
                        @if ($mediaNonCorrige)
                            <iframe src="{{ route('sujet.preview', ['id' => $sujet->id, 'type' => 'non_corrige']) }}"
                                width="100%" height="150"></iframe>
                            <div class="small text-muted mt-1">
                                {{ strtoupper($mediaNonCorrige->extension) }} • {{ round($mediaNonCorrige->size / 1048576, 2) }} MB
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('sujet.preview', ['id' => $sujet->id, 'type' => 'non_corrige']) }}"
                                    target="_blank" class="btn btn-primary btn-sm">Voir le sujet</a>
                                <a href="{{ route('sujet.preview', ['id' => $sujet->id, 'type' => 'non_corrige']) }}"
                                    download class="btn btn-outline-secondary btn-sm">Télécharger</a>
                            </div>
                        @else
                            <span class="text-muted d-block">Aucun fichier disponible</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet corrigé</label>
                        @php $mediaCorrige = $sujet->getFirstMedia('corrige'); @endphp
                        @if ($mediaCorrige)
                            <iframe src="{{ route('sujet.preview', ['id' => $sujet->id, 'type' => 'corrige']) }}"
                                width="100%" height="150"></iframe>
                            <div class="small text-muted mt-1">
                                {{ strtoupper($mediaCorrige->extension) }} • {{ round($mediaCorrige->size / 1048576, 2) }} MB
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('sujet.preview', ['id' => $sujet->id, 'type' => 'corrige']) }}"
                                    target="_blank" class="btn btn-primary btn-sm">Voir le corrigé</a>
                                <a href="{{ route('sujet.preview', ['id' => $sujet->id, 'type' => 'corrige']) }}"
                                    download class="btn btn-outline-secondary btn-sm">Télécharger</a>
                            </div>
                        @else
                            <span class="text-muted d-block">Aucun fichier disponible</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.approuve-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Confirmer ?',
                        text: form.dataset.message,
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
            });
        });
    </script>
@endsection
