@extends('backend.layouts.master')
@section('title')
    Infos Flash
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Infos Flash
        @endslot
        @slot('title')
            Infos Flash
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Liste des infos flash</h5>
                        <small class="text-muted">Affichées en bandeau dans l'en-tête du site public</small>
                    </div>
                    @can('creer-info-flash')
                        <a href="{{ route('info-flash.create') }}" class="btn btn-primary"><i class="ri ri-add-fill"></i> Créer une info flash</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Message</th>
                                    <th>Type</th>
                                    <th>Lien</th>
                                    <th>Position</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($infoFlashes as $key => $item)
                                    <tr id="row_{{ $item->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ Str::limit($item->message, 60) }}</td>
                                        <td>
                                            @php
                                                $typeBadges = [
                                                    'info' => 'bg-info-subtle text-info',
                                                    'succes' => 'bg-success-subtle text-success',
                                                    'attention' => 'bg-warning-subtle text-warning',
                                                    'urgent' => 'bg-danger-subtle text-danger',
                                                ];
                                            @endphp
                                            <span class="badge {{ $typeBadges[$item->type] ?? 'bg-secondary-subtle text-secondary' }}">{{ ucfirst($item->type) }}</span>
                                        </td>
                                        <td>
                                            @if ($item->lien)
                                                <span class="badge bg-primary-subtle text-primary">{{ $item->lien_texte ?: 'Lien' }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-primary">{{ $item->position }}</span></td>
                                        <td>
                                            @if ($item->statut === 'active')
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-danger">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @can('modifier-info-flash')
                                                        <li><a href="{{ route('info-flash.edit', $item->id) }}"
                                                                class="dropdown-item edit-item-btn"><i
                                                                        class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                Modifier</a></li>
                                                    @endcan
                                                    @can('supprimer-info-flash')
                                                        <li>
                                                            <a href="#" class="dropdown-item remove-item-btn delete"
                                                                data-id={{ $item->id }}>
                                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                                Supprimer
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Aucune info flash pour le moment.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            var route = "info-flash"
            delete_row(route);
        })
    </script>
@endsection
