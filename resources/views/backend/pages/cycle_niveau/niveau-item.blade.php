@extends('backend.layouts.master')
@section('title')
    Ajouter un Niveau
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('niveau.create') }}">Cycles & Niveaux</a>
        @endslot
        @slot('title')
            Ajouter un Niveau dans "{{ $data_niveau_parent->libelle }}"
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="ri-add-circle-line me-2"></i>
                        Ajouter un Niveau
                    </h5>
                    <small class="opacity-75">dans le cycle "{{ $data_niveau_parent->libelle }}"</small>
                </div>
                <div class="card-body">
                    <form class="needs-validation" method="post" action="{{ route('niveau.add-subCat-store') }}" novalidate id="niveauForm">
                        @csrf
                        <input type="hidden" name="niveau_parent" value="{{ $data_niveau_parent->id }}">
                        
                        <div class="mb-3">
                            <label for="libelle" class="form-label fw-semibold">
                                <i class="ri-list-unordered text-success me-1"></i>
                                Nom du Niveau <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="libelle" 
                                   class="form-control" 
                                   id="libelle"
                                   placeholder="Ex: CP, CE1, 6ème, Terminale..." 
                                   required
                                   minlength="2"
                                   maxlength="50">
                            <div class="valid-feedback">
                                <i class="ri-check-line"></i> Nom valide !
                            </div>
                            <div class="invalid-feedback">
                                <i class="ri-error-warning-line"></i> Le nom du niveau est obligatoire (2-50 caractères)
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label fw-semibold">
                                <i class="ri-toggle-line text-success me-1"></i>
                                Statut
                            </label>
                            <select name="statut" class="form-select" id="statut" required>
                                <option value="active" selected>
                                    <i class="ri-check-circle-line"></i> Activé
                                </option>
                                <option value="desactive">
                                    <i class="ri-close-circle-line"></i> Désactivé
                                </option>
                            </select>
                            <div class="form-text">
                                <i class="ri-information-line"></i> 
                                Les niveaux activés sont visibles aux utilisateurs
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="ri-save-line me-2"></i>
                                Ajouter le Niveau
                            </button>
                            <a href="{{ route('niveau.create') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-2"></i>
                                Retour à la liste
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
