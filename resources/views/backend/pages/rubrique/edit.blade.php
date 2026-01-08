@extends('backend.layouts.master')
@section('title')
    Modifier une Rubrique
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
            Rubriques
        @endslot
        @slot('title')
            Gestion des Rubriques
        @endslot
    @endcomponent
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('backend.rubrique.index') }}">Rubriques</a></li>
                            <li class="breadcrumb-item active">Modifier</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Modifier Rubrique</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('backend.rubrique.update', $rubrique) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="titre" class="form-label">Titre <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="titre" name="titre"
                                            class="form-control @error('titre') is-invalid @enderror"
                                            value="{{ old('titre', $rubrique->titre) }}" required>
                                        @error('titre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="resume" class="form-label">Résumé</label>
                                        <textarea id="resume" name="resume" class="form-control @error('resume') is-invalid @enderror" rows="3"
                                            placeholder="Résumé de la rubrique (max 500 caractères)">{{ old('resume', $rubrique->resume) }}</textarea>
                                        @error('resume')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="contenu" class="form-label">Contenu <span
                                                class="text-danger">*</span></label>
                                        <textarea id="contenu" name="contenu" class="form-control @error('contenu') is-invalid @enderror" rows="15"
                                            required>{{ old('contenu', $rubrique->contenu) }}</textarea>
                                        @error('contenu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="tags" class="form-label">Tags</label>
                                        <input type="text" id="tags" name="tags"
                                            class="form-control @error('tags') is-invalid @enderror"
                                            value="{{ old('tags', $rubrique->tags ? implode(', ', $rubrique->tags) : '') }}"
                                            placeholder="Séparez les tags par des virgules">
                                        @error('tags')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Exemple: éducation, conseil, actualité</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card border">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Paramètres de Publication</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="type_rubrique" class="form-label">Type de Rubrique <span
                                                        class="text-danger">*</span></label>
                                                <select id="type_rubrique" name="type_rubrique"
                                                    class="form-select @error('type_rubrique') is-invalid @enderror"
                                                    required>
                                                    <option value="">Sélectionner un type</option>
                                                    @foreach ($typesRubriques as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('type_rubrique', $rubrique->type_rubrique) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('type_rubrique')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="image_principale" class="form-label">Image Principale</label>
                                                @if ($rubrique->getFirstMediaUrl('image_principale'))
                                                    <div class="mb-2">
                                                        <img src="{{ $rubrique->getFirstMediaUrl('image_principale', 'thumb') }}"
                                                            alt="Image actuelle" class="img-thumbnail"
                                                            style="max-width: 150px;">
                                                        <div class="form-text">Image actuelle</div>
                                                    </div>
                                                @endif
                                                <input type="file" id="image_principale" name="image_principale"
                                                    class="form-control @error('image_principale') is-invalid @enderror"
                                                    accept="image/jpeg,image/png,image/jpg">
                                                @error('image_principale')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Format accepté: JPG, PNG (max 2MB)</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="date_publication" class="form-label">Date de
                                                    Publication</label>
                                                <input type="datetime-local" id="date_publication"
                                                    name="date_publication"
                                                    class="form-control @error('date_publication') is-invalid @enderror"
                                                    value="{{ old('date_publication', $rubrique->date_publication ? $rubrique->date_publication->format('Y-m-d\TH:i') : '') }}">
                                                @error('date_publication')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="ordre_affichage" class="form-label">Ordre d'Affichage</label>
                                                <input type="number" id="ordre_affichage" name="ordre_affichage"
                                                    class="form-control @error('ordre_affichage') is-invalid @enderror"
                                                    value="{{ old('ordre_affichage', $rubrique->ordre_affichage) }}"
                                                    min="0">
                                                @error('ordre_affichage')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="est_publie"
                                                        name="est_publie" value="1"
                                                        {{ old('est_publie', $rubrique->est_publie) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="est_publie">Publier</label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="est_featured"
                                                        name="est_featured" value="1"
                                                        {{ old('est_featured', $rubrique->est_featured) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="est_featured">Mettre en
                                                        avant</label>
                                                </div>
                                            </div>

                                            <div class="alert alert-info">
                                                <div class="d-flex justify-content-between">
                                                    <span>Vues:</span>
                                                    <strong>{{ $rubrique->nb_vues }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Créé le:</span>
                                                    <strong>{{ $rubrique->created_at->format('d/m/Y H:i') }}</strong>
                                                </div>
                                                @if ($rubrique->updated_at != $rubrique->created_at)
                                                    <div class="d-flex justify-content-between">
                                                        <span>Modifié le:</span>
                                                        <strong>{{ $rubrique->updated_at->format('d/m/Y H:i') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-content-save"></i> Enregistrer les modifications
                                        </button>
                                        <a href="{{ route('backend.rubrique.show', $rubrique) }}" class="btn btn-info">
                                            <i class="mdi mdi-eye"></i> Voir
                                        </a>
                                        <a href="{{ route('backend.rubrique.index') }}" class="btn btn-secondary">
                                            <i class="mdi mdi-arrow-left"></i> Retour
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#contenu'), {
                language: 'fr',
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
