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
            Modifier un niveau
        @endslot
    @endcomponent

    <div class="row">
      

        <div class="col-lg-10 m-auto">
            <div class="card">

                <div class="card-body">
                    <form class="row g-3 needs-validation" method="post"
                        action="{{ route('niveau.update', $data_niveau_edit['id']) }}" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label for="validationCustom01" class="form-label">Modifier une niveau </label>
                            <input type="text" name="libelle" value="{{ $data_niveau_edit['libelle'] }}"
                                class="form-control" id="validationCustom01" placeholder="niveau1" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label for="validationCustom01" class="form-label">Position </label>
                            <select name="position" class="form-control">
                                @for ($i = 1; $i <= $data_count; $i++)
                                    <option value="{{ $i }}"
                                        {{ $data_niveau_edit['position'] == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>



                        <div class="col-md-2">
                            <label for="validationCustom01" class="form-label">Statut</label>
                            <select name="statut" class="form-control">
                                <option value="active" {{ $data_niveau_edit['statut'] == 'active' ? 'selected' : '' }}>
                                    Activé
                                </option>
                                <option value="desactive"
                                    {{ $data_niveau_edit['statut'] == 'desactive' ? 'selected' : '' }}>
                                    Desactivé
                                </option>
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-2 pt-4">
                            <button type="submit" class="btn btn-primary w-100 ">Modifier</button>
                        </div>

                </div>
               
                </form>
            </div>
        </div><!-- end row -->

          <!-- ========== Start niveau list ========== -->
        @include('backend.pages.cycle_niveau.niveau-list')
        <!-- ========== End niveau list ========== -->

    </div><!-- end col -->

    <!--end row-->

@section('script')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script>
    <script src="{{ URL::asset('build/js/pages/modal.init.js') }}"></script>
    {{-- <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script> --}}
@endsection
@endsection
