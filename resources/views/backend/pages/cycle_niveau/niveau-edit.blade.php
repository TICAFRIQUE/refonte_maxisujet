@extends('backend.layouts.master')
@section('title')
   Modifier un Niveau
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('niveau.create') }}">Cycles & Niveaux</a>
        @endslot
        @slot('title')
            Modifier "{{ $data_niveau_edit->libelle }}"
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="ri-edit-2-line me-2"></i>
                        Modifier le Niveau
                    </h5>
                    <small class="opacity-75">"{{ $data_niveau_edit->libelle }}"</small>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" method="post"
                        action="{{ route('niveau.update', $data_niveau_edit->id) }}" novalidate id="editForm">
                        @csrf
                        <div class="col-md-6">
                            <label for="libelle" class="form-label fw-semibold">
                                <i class="ri-text text-warning me-1"></i>
                                Nom du Niveau <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="libelle" 
                                   value="{{ $data_niveau_edit->libelle }}"
                                   class="form-control" 
                                   id="libelle" 
                                   placeholder="Nom du niveau" 
                                   required
                                   minlength="2"
                                   maxlength="50">
                            <div class="valid-feedback">
                                <i class="ri-check-line"></i> Nom valide !
                            </div>
                            <div class="invalid-feedback">
                                <i class="ri-error-warning-line"></i> Le nom est obligatoire (2-50 caractères)
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="position" class="form-label fw-semibold">
                                <i class="ri-sort-asc text-info me-1"></i>
                                Position
                            </label>
                            <select name="position" class="form-select" id="position" required>
                                @for ($i = 1; $i <= $data_count; $i++)
                                    <option value="{{ $i }}"
                                        {{ $data_niveau_edit->position == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <div class="form-text">
                                <i class="ri-information-line"></i> Ordre d'affichage
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="statut" class="form-label fw-semibold">
                                <i class="ri-toggle-line text-success me-1"></i>
                                Statut
                            </label>
                            <select name="statut" class="form-select" id="statut" required>
                                <option value="active" {{ $data_niveau_edit->statut == 'active' ? 'selected' : '' }}>
                                    <i class="ri-check-circle-line"></i> Activé
                                </option>
                                <option value="desactive"
                                    {{ $data_niveau_edit->statut == 'desactive' ? 'selected' : '' }}>
                                    <i class="ri-close-circle-line"></i> Désactivé
                                </option>
                            </select>
                            <div class="form-text">
                                <i class="ri-information-line"></i> Visibilité publique
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-grid w-100">
                                <button type="submit" class="btn btn-warning text-white">
                                    <i class="ri-save-line me-1"></i> Modifier
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('niveau.create') }}" class="btn btn-outline-secondary">
                                    <i class="ri-arrow-left-line me-1"></i> Retour à la liste
                                </a>
                                <button type="reset" class="btn btn-outline-warning">
                                    <i class="ri-refresh-line me-1"></i> Réinitialiser
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- end col -->

          <!-- ========== Start niveau list ========== -->
        @include('backend.pages.cycle_niveau.niveau-list')
        <!-- ========== End niveau list ========== -->

    </div><!-- end row -->

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    
    <script>
        // Validation personnalisée
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Formatage automatique du nom
        document.getElementById('libelle').addEventListener('input', function(e) {
            let value = e.target.value;
            // Supprimer les caractères spéciaux
            value = value.replace(/[^a-zA-ZÀ-ÿ0-9\s]/g, '');
            e.target.value = value;
        });
    </script>
@endsection
@endsection
