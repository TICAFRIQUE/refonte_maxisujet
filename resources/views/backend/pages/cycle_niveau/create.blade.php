@extends('backend.layouts.master')
@section('title')
    Categorie
@endsection
@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            niveau & cycle
        @endslot
        @slot('title')
            Gerer les niveaux & cycles
        @endslot
    @endcomponent

    <div class="row">

        <div class="col-lg-3">
            <div class="card">

                <div class="card-body">
                    <form class="row g-3 needs-validation" method="post" action="{{ route('niveau.store') }}" novalidate>
                        @csrf
                        <div class="col-md-12">
                            <label for="validationCustom01" class="form-label">Ajouter un cycle </label>
                            <input type="text" name="libelle" class="form-control" id="validationCustom01"
                                placeholder="Entrer le cycle" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="validationCustom01" class="form-label">Statut</label>
                            <select name="statut" class="form-control">
                                <option value="active">Activé</option>
                                <option value="desactive">Desactivé</option>

                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-12 mt-4">
                            <label for="validationCustom01" class="form-label"></label>
                            <button type="submit" class="btn btn-primary w-100 ">Valider</button>
                        </div>

                </div>

                </form>
            </div>
        </div>

        <!-- ========== Start niveaux list ========== -->
        @include('backend.pages.cycle_niveau.niveau-list', ['data_niveaux' => $data_niveaux])
        <!-- ========== End niveaux list ========== -->

        <!-- end row -->
    </div><!-- end col -->

    <!--end row-->

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script> --}}
@endsection
@endsection
