@extends('backend.layouts.master')
@section('title')
    Cycles & Niveaux
@endsection

@section('css')
    <link href="{{ URL::asset('assets/css/cycle-niveau.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Administration
        @endslot
        @slot('title')
            Gestion des Cycles & Niveaux Scolaires
        @endslot
    @endcomponent

    <!-- Statistiques en haut -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0 text-white">{{ $data_niveaux->count() }}</h4>
                            <p class="mb-0">Cycles Totaux</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-book-2-line fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $data_niveaux->where('statut', 'active')->count() }}</h4>
                            <p class="mb-0">Cycles Actifs</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-checkbox-circle-line fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $data_niveaux->sum('children_count') }}</h4>
                            <p class="mb-0">Niveaux Totaux</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-list-unordered fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $data_niveaux->where('statut', 'desactive')->count() }}</h4>
                            <p class="mb-0">Cycles Inactifs</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-close-circle-line fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 order-lg-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="ri-add-circle-line me-2"></i>
                        Créer un Nouveau Cycle
                    </h5>
                </div>
                <div class="card-body">
                    <form class="needs-validation" method="post" action="{{ route('niveau.store') }}" novalidate id="cycleForm">
                        @csrf
                        <div class="mb-3">
                            <label for="libelle" class="form-label fw-semibold">
                                <i class="ri-book-line text-primary me-1"></i>
                                Nom du Cycle <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="libelle" 
                                   class="form-control" 
                                   id="libelle"
                                   placeholder="Ex: Primaire, Collège, Lycée..." 
                                   required
                                   minlength="2"
                                   maxlength="50">
                            <div class="valid-feedback">
                                <i class="ri-check-line"></i> Nom valide !
                            </div>
                            <div class="invalid-feedback">
                                <i class="ri-error-warning-line"></i> Le nom du cycle est obligatoire (2-50 caractères)
                            </div>
                            <small class="text-muted">Le nom sera automatiquement formaté avec une majuscule</small>
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
                                Les cycles activés sont visibles aux utilisateurs
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="ri-save-line me-2"></i>
                                Créer le Cycle
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-2"></i>
                                Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Guide d'utilisation -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="ri-lightbulb-line text-warning me-2"></i>
                        Guide d'utilisation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <i class="ri-number-1 text-primary me-2"></i>
                            <small>Créez d'abord un <strong>cycle</strong> (ex: Primaire)</small>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <i class="ri-number-2 text-success me-2"></i>
                            <small>Ajoutez des <strong>niveaux</strong> dans chaque cycle</small>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <i class="ri-number-3 text-info me-2"></i>
                            <small>Utilisez le <strong>glisser-déposer</strong> pour réorganiser</small>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <i class="ri-number-4 text-warning me-2"></i>
                            <small>Activez/désactivez selon vos besoins</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== Start niveaux list ========== -->
        @include('backend.pages.cycle_niveau.niveau-list', ['data_niveaux' => $data_niveaux])
        <!-- ========== End niveaux list ========== -->

    </div><!-- end row -->

@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="{{ URL::asset('assets/js/cycle-niveau.js') }}"></script>
    
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
            value = value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
            e.target.value = value;
        });
    </script>
@endsection
