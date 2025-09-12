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
            Modifier
        @endslot
        @slot('title')
            Sujet
        @endslot
    @endcomponent



    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Modifier un sujets</h5>
                    <a href="{{ route('sujet.create') }}" class="btn btn-primary">Creer un sujet</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sujet.update', $sujet->id) }}" enctype="multipart/form-data"
                        class="needs-validation">
                        @csrf

                        <div class="row">
                            <!---col-md-8--->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="categorie_id" class="form-label">Catégorie</label>
                                    <select name="categorie_id" id="categorie_id" class="form-control" required>
                                        <option value="">Sélectionner</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ $sujet->categorie_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="niveaux" class="form-label">Niveaux</label>
                                    <select name="niveaux[]" id="niveaux" class="form-control" data-choices
                                        data-choices-multiple-groups="true" multiple required>
                                        @foreach ($niveaux as $niveau)
                                            @include('backend.pages.sujet.partials.subNiveauOption', [
                                                'sousNiveaux' => $niveau,
                                                'selectedNiveaux' => $sujet->niveaux->pluck('id')->toArray(),
                                            ])
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="matiere_id" class="form-label">Matière</label>
                                    <select name="matiere_id" id="matiere_id" class="form-control" required>
                                        <option value="">Sélectionner</option>
                                        @foreach ($matieres as $mat)
                                            <option value="{{ $mat->id }}"
                                                {{ $sujet->matiere_id == $mat->id ? 'selected' : '' }}>
                                                {{ $mat->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $sujet->description) }}</textarea>
                                </div>
                            </div>

                            <!---col-md-4--->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="non_corrige" class="form-label">Fichier non corrigé</label>
                                    <input type="file" name="non_corrige" id="non_corrige" class="form-control">
                                    @if ($sujet->getFirstMediaUrl('non_corrige'))
                                        <div class="mt-2">
                                            <iframe src="{{ $sujet->getFirstMediaUrl('non_corrige') }}" width="100%"
                                                height="150"></iframe>
                                            <a href="{{ $sujet->getFirstMediaUrl('non_corrige') }}" target="_blank"
                                                class="btn btn-link">Voir le fichier actuel</a>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="corrige" class="form-label">Fichier corrigé</label>
                                    <input type="file" name="corrige" id="corrige" class="form-control">
                                    @if ($sujet->getFirstMediaUrl('corrige'))
                                        <div class="mt-2">
                                            <iframe src="{{ $sujet->getFirstMediaUrl('corrige') }}" width="100%"
                                                height="150"></iframe>
                                            <a href="{{ $sujet->getFirstMediaUrl('corrige') }}" target="_blank"
                                                class="btn btn-link">Voir le fichier actuel</a>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="annee" class="form-label">Année</label>
                                    <select name="annee" id="annee" class="form-control">
                                        <option value="" selected> Sélectionner</option>
                                        @for ($year = date('Y'); $year >= 1990; $year--)
                                            <option value="{{ $year }}"
                                                {{ $sujet->annee == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select name="statut" id="statut" class="form-control">
                                        <option value="active" {{ $sujet->statut == 'active' ? 'selected' : '' }}>Activé
                                        </option>
                                        <option value="desactive" {{ $sujet->statut == 'desactive' ? 'selected' : '' }}>
                                            Désactivé</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="approuve" class="form-label">Approuvé</label>
                                    <select name="approuve" id="approuve" class="form-control">
                                        <option value="1" {{ $sujet->approuve ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ !$sujet->approuve ? 'selected' : '' }}>Non</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script> --}}
    <script src="{{ URL::asset('build/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <script src="{{ URL::asset('build/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-product-create.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>


    <script>
        // $(document).ready(function() {
        //     $('#niveaux').select2({
        //         placeholder: "Sélectionner les niveaux",
        //         allowClear: true
        //     });
        // });
    </script>
@endsection
