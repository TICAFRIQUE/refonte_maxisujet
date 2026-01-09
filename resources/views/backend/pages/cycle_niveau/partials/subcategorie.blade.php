<div class="ps-4 mt-2">
    <div class="border-start border-2 border-info-subtle ps-3">
        @foreach ($niveau_child as $niveau)
            <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <i class="ri-corner-down-right-line text-muted"></i>
                    </div>
                    <div class="me-2">
                        <div class="avatar-xs bg-info-subtle rounded-circle">
                            <div class="avatar-title bg-transparent text-info">
                                <i class="ri-list-unordered fs-6"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <span class="fw-medium text-dark">{{ $niveau->libelle }}</span>
                        @if($niveau->statut === 'active')
                            <i class="ri-check-circle-fill text-success ms-1" title="Actif"></i>
                        @else
                            <i class="ri-pause-circle-fill text-warning ms-1" title="Inactif"></i>
                        @endif
                        
                        @if($niveau->children->count() > 0)
                            <span class="badge bg-secondary-subtle text-secondary ms-2">
                                <i class="ri-list-unordered me-1"></i>{{ $niveau->children->count() }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('niveau.add-subCat', $niveau->id) }}" 
                       class="btn btn-outline-primary btn-sm" title="Ajouter un sous-niveau">
                        <i class="ri-add-line"></i>
                    </a>
                    <a href="{{ route('niveau.edit', $niveau->id) }}" 
                       class="btn btn-outline-success btn-sm" title="Modifier">
                        <i class="ri-edit-2-line"></i>
                    </a>
                    @if(count($niveau->children) == 0)
                        <a href="#" class="btn btn-outline-danger btn-sm delete" 
                           data-id="{{ $niveau->id }}" title="Supprimer">
                            <i class="ri-delete-bin-2-line"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            @if($niveau->children->count() > 0)
                @include('backend.pages.cycle_niveau.partials.subcategorie', [
                    'niveau_child' => $niveau->children,
                ])
            @endif
        @endforeach
    </div>
</div>


