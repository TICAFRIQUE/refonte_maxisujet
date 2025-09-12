@extends('backend.layouts.master')
@section('title')
    niveau
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            niveau
        @endslot
        @slot('title')
            Créer un sous-niveau
        @endslot
    @endcomponent

    <div class="row">

        <!-- ========== Start niveau list ========== -->
        @include('backend.pages.cycle_niveau.niveau-list')
        <!-- ========== End niveau list ========== -->

        <div class="col-lg-3">
            <div class="card">

                <div class="card-body">
                    <form class="row g-3 needs-validation" method="post" action="{{ route('niveau.add-subCat-store') }}"
                        novalidate>
                        @csrf
                        <div class="col-md-12">
                            <h5>Enregistrer un sous élement dans : <strong
                                    class="text-primary">{{ $data_niveau_parent['libelle'] }}</strong></h5>
                            <input readonly type="text" name="niveau_parent" value="{{ $data_niveau_parent['id'] }}"
                                class="form-control" id="validationCustom01" placeholder="niveau1" hidden>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label for="validationCustom01" class="form-label">Nom de la sous niveau</label>
                            <input type="text" name="libelle" class="form-control" id="validationCustom01" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>


                        <div class="col-md-4">
                            <label for="validationCustom01" class="form-label">Statut</label>
                            <select name="statut" class="form-control">
                                <option value="active">Activé</option>
                                <option value="desactive">Desactivé</option>

                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>


                        <div class="col-md-12 pt-2">
                            <label for="validationCustom01" class="form-label"></label>
                            <button type="submit" class="btn btn-primary w-100 ">Valider</button>
                        </div>
                </div>

                </form>
            </div>
        </div><!-- end row -->




    </div>

    <!-- end col -->

    <!--end row-->

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script> --}}
@endsection
@endsection
