  <div class="col-lg-9">
      <div class="card">
          <div class="card-body">
              <div class="d-flex justify-content-between">
                  <h5>Liste des cyles & niveaux</h5>
                  <a href="{{ route('niveau.create') }}" class="btn btn-primary">
                      <i class="ri ri-add-fill"></i>
                      Ajouter un cycle</a>
              </div>
              <!-- Accordions with Plus Icon -->
              @foreach ($data_niveaux as $key => $niveau)
                  <div>

                      <hr>
                      <li class="" style="list-style: none">
                          <i class="ri-drag-move-fill align-bottom handle"></i> <a class="fs-5 fw-medium"
                              href="#">{{ $niveau->libelle }}</a>
                          <span class="d-flex gap-3 float-end">

                              <a href="{{ route('niveau.add-subCat', $niveau['id']) }}" class="fs-5"> <i
                                      class=" ri ri-add-circle-fill ml-4">Ajouter un niveau</i>
                              </a>

                              <a href="{{ route('niveau.edit', $niveau['id']) }}" class="fs-5 "> <i
                                      class=" ri ri-edit-2-fill ml-4 text-success ">Modifier le
                                      cycle</i></a>


                              @if ($niveau['children_count'] == 0)
                                  <a href="#" data-id="{{ $niveau['id'] }}" class="fs-5 delete">
                                      <i class="ri ri-delete-bin-2-line text-danger ">supprimer</i>
                                  </a>
                              @endif
                          </span>

                          <!-- Enfants -->
                          @if ($niveau->children->count() > 0)
                              @include('backend.pages.cycle_niveau.partials.subcategorie', [
                                  'niveau_child' => $niveau->children,
                              ])
                          @endif
                      </li>







                      {{-- <li class="d-flex align-items-center justify-content-between py-2 border-bottom"
                          style="list-style: none;">
                          <div class="d-flex align-items-center">
                              <!-- Handle pour drag -->
                              <i class="ri-drag-move-fill handle me-2 text-muted"></i>

                              <!-- Nom du niveau -->
                              <a href="#" class="fs-5 fw-medium text-dark text-decoration-none">
                                  {{ $niveau->libelle }}
                              </a>
                          </div>

                          <div class="d-flex gap-3">

                              <!-- Ajouter -->
                              <a href="{{ route('niveau.add-subCat', $niveau['id']) }}"
                                  class="text-primary d-flex align-items-center">
                                  <i class="ri-add-circle-fill me-1"></i> <span>Ajouter</span>
                              </a>

                              <!-- Modifier -->
                              <a href="{{ route('niveau.edit', $niveau['id']) }}"
                                  class="text-success d-flex align-items-center">
                                  <i class="ri-edit-2-fill me-1"></i> <span>Modifier</span>
                              </a>


                              <!-- Supprimer (seulement si pas d’enfants) -->
                              @if ($niveau['children_count'] == 0)
                                  <a href="#" data-id="{{ $niveau['id'] }}"
                                      class="delete text-danger d-flex align-items-center">
                                      <i class="ri-delete-bin-2-line me-1"></i> <span>Supprimer</span>
                                  </a>
                              @endif
                          </div>

                          <!-- Enfants -->
                          @if ($niveau->children->count() > 0)
                              @include('backend.pages.cycle_niveau.partials.subcategorie', [
                                  'niveau_child' => $niveau->children,
                              ])
                          @endif
                      </li> --}}

                  </div>
              @endforeach
          </div>

      </div>
  </div><!-- end row -->



  @section('script')
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"
          integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

      <script>
          $(document).ready(function() {
              $('.delete').on("click", function(e) {
                  e.preventDefault();
                  var Id = $(this).attr('data-id');
                  Swal.fire({
                      title: 'Etes-vous sûr(e) de vouloir supprimer ?',
                      text: "Cette action est irréversible!",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonText: 'Supprimer!',
                      cancelButtonText: 'Annuler',
                      customClass: {
                          confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                          cancelButton: 'btn btn-danger w-xs mt-2',
                      },
                      buttonsStyling: false,
                      showCloseButton: true
                  }).then((result) => {
                      if (result.isConfirmed) {
                          $.ajax({
                              type: "GET",
                              url: "/admin/niveau/delete/" + Id,
                              dataType: "json",

                              success: function(response) {
                                  if (response.status == 200) {
                                      Swal.fire({
                                          title: 'Supproimé!',
                                          text: 'Supprimé avec succes.',
                                          icon: 'success',
                                          customClass: {
                                              confirmButton: 'btn btn-primary w-xs mt-2',
                                          },
                                          buttonsStyling: false
                                      })

                                      //   $('#row_' + Id).remove();
                                      location.reload();
                                  }
                              }
                          });
                      }
                  });
              });
          });
      </script>
  @endsection
