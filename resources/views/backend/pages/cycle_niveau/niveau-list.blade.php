  <div class="col-lg-8 order-lg-1">
      <div class="card border-0 shadow-sm">
          <div class="card-header bg-light border-bottom">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h5 class="card-title mb-0">
                          <i class="ri-list-ordered text-primary me-2"></i>
                          Hiérarchie des Cycles & Niveaux
                      </h5>
                      <small class="text-muted">Glisser-déposer pour réorganiser l'ordre</small>
                  </div>
                  <div class="d-flex gap-2">
                      <div class="input-group input-group-sm" style="width: 250px;">
                          <span class="input-group-text">
                              <i class="ri-search-line"></i>
                          </span>
                          <input type="text" class="form-control" placeholder="Rechercher..." id="searchInput">
                      </div>
                      <a href="{{ route('niveau.create') }}" class="btn btn-primary btn-sm">
                          <i class="ri-add-line me-1"></i>
                          Nouveau Cycle
                      </a>
                  </div>
              </div>
          </div>
          <div class="card-body p-0">
              <div class="list-group list-group-flush" id="niveau-list">
                  @forelse ($data_niveaux as $key => $niveau)
                      <div class="list-group-item border-0 py-3 niveau-item" data-id="{{ $niveau->id }}">
                          <div class="d-flex align-items-start justify-content-between">
                              <div class="d-flex align-items-center flex-grow-1">
                                  <!-- Handle pour drag & drop -->
                                  <div class="drag-handle me-3 text-muted cursor-pointer">
                                      <i class="ri-drag-move-2-line fs-5"></i>
                                  </div>
                                  
                                  <!-- Icône du cycle -->
                                  <div class="me-3">
                                      <div class="avatar-sm bg-primary-subtle rounded">
                                          <div class="avatar-title bg-transparent text-primary">
                                              <i class="ri-book-2-line fs-4"></i>
                                          </div>
                                      </div>
                                  </div>

                                  <!-- Informations du cycle -->
                                  <div class="flex-grow-1">
                                      <div class="d-flex align-items-center gap-2 mb-1">
                                          <h6 class="mb-0 fw-semibold text-dark">{{ $niveau->libelle }}</h6>
                                          
                                          <!-- Badge de statut -->
                                          @if($niveau->statut === 'active')
                                              <span class="badge bg-success-subtle text-success">
                                                  <i class="ri-check-line me-1"></i>Actif
                                              </span>
                                          @else
                                              <span class="badge bg-warning-subtle text-warning">
                                                  <i class="ri-pause-line me-1"></i>Inactif
                                              </span>
                                          @endif
                                          
                                          <!-- Compteur d'enfants -->
                                          @if($niveau->children_count > 0)
                                              <span class="badge bg-info-subtle text-info">
                                                  <i class="ri-list-unordered me-1"></i>{{ $niveau->children_count }} niveau(x)
                                              </span>
                                          @endif
                                      </div>
                                      
                                      <div class="text-muted small">
                                          <i class="ri-calendar-line me-1"></i>
                                          Position: {{ $niveau->position ?? 'Non définie' }}
                                          @if($niveau->children_count > 0)
                                              | {{ $niveau->children_count }} sous-niveau(x)
                                          @endif
                                      </div>
                                  </div>
                              </div>

                              <!-- Actions -->
                              <div class="dropdown">
                                  <button class="btn btn-light btn-sm dropdown-toggle" type="button" 
                                          data-bs-toggle="dropdown" aria-expanded="false">
                                      <i class="ri-more-2-fill"></i>
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">
                                      <li>
                                          <a class="dropdown-item" href="{{ route('niveau.add-subCat', $niveau->id) }}">
                                              <i class="ri-add-circle-line text-primary me-2"></i>
                                              Ajouter un niveau
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item" href="{{ route('niveau.edit', $niveau->id) }}">
                                              <i class="ri-edit-2-line text-success me-2"></i>
                                              Modifier
                                          </a>
                                      </li>
                                      @if($niveau->children_count == 0)
                                          <li><hr class="dropdown-divider"></li>
                                          <li>
                                              <a class="dropdown-item text-danger delete" href="#" 
                                                 data-id="{{ $niveau->id }}">
                                                  <i class="ri-delete-bin-2-line me-2"></i>
                                                  Supprimer
                                              </a>
                                          </li>
                                      @endif
                                  </ul>
                              </div>
                          </div>

                          <!-- Enfants (sous-niveaux) -->
                          @if($niveau->children->count() > 0)
                              <div class="mt-3 ps-5">
                                  <div class="border-start border-2 border-primary-subtle ps-3">
                                      @foreach($niveau->children as $child)
                                          <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light">
                                              <div class="d-flex align-items-center">
                                                  <div class="me-2">
                                                      <i class="ri-corner-down-right-line text-muted"></i>
                                                  </div>
                                                  <div>
                                                      <span class="fw-medium">{{ $child->libelle }}</span>
                                                      @if($child->statut === 'active')
                                                          <i class="ri-check-circle-fill text-success ms-1" title="Actif"></i>
                                                      @else
                                                          <i class="ri-pause-circle-fill text-warning ms-1" title="Inactif"></i>
                                                      @endif
                                                  </div>
                                              </div>
                                              
                                              <div class="btn-group btn-group-sm">
                                                  <a href="{{ route('niveau.add-subCat', $child->id) }}" 
                                                     class="btn btn-outline-primary btn-sm" title="Ajouter">
                                                      <i class="ri-add-line"></i>
                                                  </a>
                                                  <a href="{{ route('niveau.edit', $child->id) }}" 
                                                     class="btn btn-outline-success btn-sm" title="Modifier">
                                                      <i class="ri-edit-2-line"></i>
                                                  </a>
                                                  @if($child->children->count() == 0)
                                                      <a href="#" class="btn btn-outline-danger btn-sm delete" 
                                                         data-id="{{ $child->id }}" title="Supprimer">
                                                          <i class="ri-delete-bin-2-line"></i>
                                                      </a>
                                                  @endif
                                              </div>
                                          </div>
                                          
                                          <!-- Récursion pour les sous-sous-niveaux -->
                                          @if($child->children->count() > 0)
                                              @include('backend.pages.cycle_niveau.partials.subcategorie', [
                                                  'niveau_child' => $child->children,
                                              ])
                                          @endif
                                      @endforeach
                                  </div>
                              </div>
                          @endif
                      </div>
                  @empty
                      <div class="text-center py-5">
                          <div class="mb-3">
                              <i class="ri-book-open-line display-4 text-muted"></i>
                          </div>
                          <h5 class="text-muted">Aucun cycle créé</h5>
                          <p class="text-muted mb-3">Commencez par créer votre premier cycle scolaire</p>
                          <a href="{{ route('niveau.create') }}" class="btn btn-primary">
                              <i class="ri-add-line me-1"></i>
                              Créer un cycle
                          </a>
                      </div>
                  @endforelse
              </div>
          </div>
      </div>
  </div><!-- end col -->


  @section('script')
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"
          integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

      <script>
          $(document).ready(function() {
              // Fonction de suppression améliorée
              $('.delete').on("click", function(e) {
                  e.preventDefault();
                  var Id = $(this).attr('data-id');
                  var itemName = $(this).closest('.niveau-item').find('.fw-semibold').text() || 'cet élément';
                  
                  Swal.fire({
                      title: 'Confirmer la suppression',
                      html: `Êtes-vous sûr(e) de vouloir supprimer <strong>"${itemName}"</strong> ?<br><small class="text-muted">Cette action est irréversible!</small>`,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonText: '<i class="ri-delete-bin-line me-1"></i> Supprimer',
                      cancelButtonText: '<i class="ri-close-line me-1"></i> Annuler',
                      customClass: {
                          confirmButton: 'btn btn-danger w-xs me-2 mt-2',
                          cancelButton: 'btn btn-secondary w-xs mt-2',
                      },
                      buttonsStyling: false,
                      showCloseButton: true
                  }).then((result) => {
                      if (result.isConfirmed) {
                          // Afficher le loader
                          Swal.fire({
                              title: 'Suppression en cours...',
                              allowOutsideClick: false,
                              didOpen: () => {
                                  Swal.showLoading();
                              }
                          });

                          $.ajax({
                              type: "GET",
                              url: "/admin/niveau/delete/" + Id,
                              dataType: "json",
                              success: function(response) {
                                  if (response.status == 200) {
                                      Swal.fire({
                                          title: 'Supprimé!',
                                          text: `"${itemName}" a été supprimé avec succès.`,
                                          icon: 'success',
                                          customClass: {
                                              confirmButton: 'btn btn-success w-xs mt-2',
                                          },
                                          buttonsStyling: false
                                      }).then(() => {
                                          location.reload();
                                      });
                                  } else {
                                      Swal.fire({
                                          title: 'Suppression impossible',
                                          text: response.message || 'Une erreur est survenue lors de la suppression.',
                                          icon: 'error',
                                          customClass: {
                                              confirmButton: 'btn btn-danger w-xs mt-2',
                                          },
                                          buttonsStyling: false
                                      });
                                  }
                              },
                              error: function(xhr, status, error) {
                                  Swal.fire({
                                      title: 'Erreur!',
                                      text: 'Une erreur est survenue lors de la suppression.',
                                      icon: 'error',
                                      customClass: {
                                          confirmButton: 'btn btn-danger w-xs mt-2',
                                      },
                                      buttonsStyling: false
                                  });
                              }
                          });
                      }
                  });
              });

              // Fonction de recherche
              $('#searchInput').on('input', function() {
                  var searchTerm = $(this).val().toLowerCase();
                  $('.niveau-item').each(function() {
                      var itemText = $(this).text().toLowerCase();
                      if (itemText.includes(searchTerm)) {
                          $(this).show();
                      } else {
                          $(this).hide();
                      }
                  });
              });
          });
      </script>
  @endsection
