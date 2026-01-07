@extends('frontend.layouts.front_app')

@section('content')
    <!-- Header avec gradient -->
    <div class="container-fluid py-4" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); min-height: 200px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="text-white mb-2 fw-bold">
                        <i class="bi bi-collection me-2"></i>Mes sujets publiés
                    </h1>
                    <p class="text-white-50 mb-0">Gérez et modifiez vos contributions à la communauté</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('user.sujet.create') }}" 
                       class="btn btn-light btn-lg rounded-pill px-4 py-2 fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau sujet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <!-- Breadcrumb moderne -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light rounded-pill shadow-sm px-4 py-3">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.dashboard') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-speedometer2 me-1"></i>Mon espace
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Mes sujets publiés</li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-25 rounded-circle p-2 me-3">
                        <i class="bi bi-check-circle-fill text-success"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold text-success">Succès !</h6>
                        <div class="text-muted small">{{ session('success') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistiques rapides -->
        <div class="row g-3 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="bi bi-files text-primary"></i>
                        </div>
                        <h4 class="fw-bold text-primary">{{ $sujets->total() ?? $sujets->count() }}</h4>
                        <small class="text-muted">Total des sujets</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <h4 class="fw-bold text-success">{{ $sujets->where('approuve', 1)->count() ?? 0 }}</h4>
                        <small class="text-muted">Approuvés</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="bi bi-clock text-warning"></i>
                        </div>
                        <h4 class="fw-bold text-warning">{{ $sujets->where('approuve', 0)->count() ?? 0 }}</h4>
                        <small class="text-muted">En attente</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="bi bi-calendar-month text-info"></i>
                        </div>
                        <h4 class="fw-bold text-info">{{ $sujets->where('created_at', '>=', now()->startOfMonth())->count() ?? 0 }}</h4>
                        <small class="text-muted">Ce mois</small>
                    </div>
                </div>
            </div>
        </div>

        @if ($sujets->isEmpty())
            <!-- État vide moderne -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <i class="bi bi-file-earmark-plus text-primary" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold text-primary mb-3">Aucun sujet publié</h3>
                            <p class="text-muted mb-4 lead">
                                Vous n'avez pas encore publié de sujet. Commencez à partager vos ressources pédagogiques avec la communauté !
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('user.sujet.create') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                                    <i class="bi bi-plus-circle me-2"></i>Publier mon premier sujet
                                </a>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5">
                                    <i class="bi bi-arrow-left me-2"></i>Retour au dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Filtres et tri -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <select class="form-select" id="filterStatus">
                                        <option value="">Tous les statuts</option>
                                        <option value="1">Approuvés</option>
                                        <option value="0">En attente</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="filterCategory">
                                        <option value="">Toutes les catégories</option>
                                        @foreach($sujets->unique('categorie_id')->pluck('categorie')->filter() as $categorie)
                                            <option value="{{ $categorie->id }}">{{ $categorie->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="sortBy">
                                        <option value="recent">Plus récents</option>
                                        <option value="old">Plus anciens</option>
                                        <option value="name">Nom A-Z</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="search" class="form-control form-control-lg border-2" placeholder="Rechercher un sujet..." id="searchInput">
                        <button class="btn btn-outline-primary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Liste des sujets en cartes -->
            <div class="row g-4" id="sujetsContainer">
                @foreach ($sujets as $sujet)
                    <div class="col-lg-6 col-xl-4 sujet-card" 
                         data-status="{{ $sujet->approuve }}" 
                         data-category="{{ $sujet->categorie_id }}" 
                         data-created="{{ $sujet->created_at->timestamp }}"
                         data-name="{{ strtolower($sujet->libelle) }}">
                        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden">
                            <!-- Badge de statut -->
                            <div class="position-absolute top-0 end-0 z-3">
                                @if($sujet->approuve == 1)
                                    <span class="badge bg-success rounded-bottom-start px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Approuvé
                                    </span>
                                @else
                                    <span class="badge bg-warning rounded-bottom-start px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>En attente
                                    </span>
                                @endif
                            </div>

                            <!-- En-tête coloré -->
                            <div class="card-header border-0 p-0 position-relative" 
                                 style="background: linear-gradient(135deg, {{ $sujet->approuve ? '#28a745' : '#ffc107' }} 0%, {{ $sujet->approuve ? '#20c997' : '#fd7e14' }} 100%); height: 80px;">
                                <div class="position-absolute bottom-0 start-0 p-3 text-white">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white bg-opacity-25 rounded-circle p-2 me-2">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <small class="fw-semibold opacity-90">{{ $sujet->code ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-3 text-truncate" title="{{ $sujet->libelle }}">
                                    {{ $sujet->libelle }}
                                </h5>
                                
                                <div class="row g-2 mb-3 small text-muted">
                                    <div class="col-12">
                                        <i class="bi bi-folder me-1 text-primary"></i>
                                        <strong>{{ $sujet->categorie->libelle ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-12">
                                        <i class="bi bi-calendar3 me-1 text-success"></i>
                                        {{ $sujet->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>

                                @if($sujet->description)
                                    <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $sujet->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="card-footer bg-light border-0 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('sujet.front.show', $sujet->libelle) }}" 
                                           class="btn btn-outline-primary btn-sm rounded-pill" title="Consulter">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('user.sujet.edit', $sujet->id) }}" 
                                           class="btn btn-outline-warning btn-sm rounded-pill" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm rounded-pill delete-btn" 
                                                data-id="{{ $sujet->id }}" 
                                                data-name="{{ $sujet->libelle }}" 
                                                title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            <i class="bi bi-eye me-1"></i>{{ rand(10, 150) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($sujets->hasPages())
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $sujets->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>

@endsection

@push('styles')
<style>
    .sujet-card {
        transition: all 0.3s ease;
    }
    .sujet-card:hover .card {
        transform: translateY(-5px);
    }
    .card {
        transition: all 0.3s ease;
    }
    .btn-sm.rounded-pill {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .search-highlight {
        background-color: #fff3cd;
        animation: highlight 2s ease-out;
    }
    @keyframes highlight {
        from { background-color: #ffeaa7; }
        to { background-color: transparent; }
    }
</style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Gestion de la suppression
            $('.delete-btn').on("click", function(e) {
                e.preventDefault();
                const sujetId = $(this).data('id');
                const sujetName = $(this).data('name');
                
                Swal.fire({
                    title: 'Supprimer ce sujet ?',
                    html: `Vous êtes sur le point de supprimer :<br><strong>"${sujetName}"</strong>`,
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="bi bi-trash me-1"></i>Supprimer',
                    cancelButtonText: '<i class="bi bi-x-circle me-1"></i>Annuler',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn fw-bold',
                        cancelButton: 'btn fw-bold'
                    },
                    buttonsStyling: false,
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Animation de suppression
                        const card = $(this).closest('.sujet-card');
                        card.addClass('animate__animated animate__fadeOut');
                        
                        $.ajax({
                            type: "GET",
                            url: `/user/sujet/delete/${sujetId}`,
                            dataType: "json",
                            success: function(response) {
                                if (response.status == 200) {
                                    Swal.fire({
                                        title: 'Supprimé !',
                                        text: 'Le sujet a été supprimé avec succès.',
                                        icon: 'success',
                                        confirmButtonColor: '#28a745',
                                        customClass: {
                                            confirmButton: 'btn btn-success fw-bold'
                                        },
                                        buttonsStyling: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    card.removeClass('animate__animated animate__fadeOut');
                                    Swal.fire({
                                        title: 'Erreur !',
                                        text: 'Impossible de supprimer le sujet.',
                                        icon: 'error',
                                        confirmButtonColor: '#dc3545'
                                    });
                                }
                            },
                            error: function() {
                                card.removeClass('animate__animated animate__fadeOut');
                                Swal.fire({
                                    title: 'Erreur !',
                                    text: 'Une erreur est survenue lors de la suppression.',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        });
                    }
                });
            });

            // Système de filtrage et recherche
            const filterCards = () => {
                const statusFilter = $('#filterStatus').val();
                const categoryFilter = $('#filterCategory').val();
                const searchTerm = $('#searchInput').val().toLowerCase();
                const sortBy = $('#sortBy').val();
                
                let cards = $('.sujet-card').toArray();
                
                // Filtrage
                cards.forEach(card => {
                    const $card = $(card);
                    const status = $card.data('status').toString();
                    const category = $card.data('category').toString();
                    const name = $card.data('name');
                    
                    let show = true;
                    
                    if (statusFilter && status !== statusFilter) show = false;
                    if (categoryFilter && category !== categoryFilter) show = false;
                    if (searchTerm && !name.includes(searchTerm)) show = false;
                    
                    if (show) {
                        $card.removeClass('d-none').addClass('animate__animated animate__fadeIn');
                        if (searchTerm && name.includes(searchTerm)) {
                            $card.addClass('search-highlight');
                            setTimeout(() => $card.removeClass('search-highlight'), 2000);
                        }
                    } else {
                        $card.addClass('d-none').removeClass('animate__animated animate__fadeIn');
                    }
                });
                
                // Tri
                const visibleCards = cards.filter(card => !$(card).hasClass('d-none'));
                visibleCards.sort((a, b) => {
                    const $a = $(a), $b = $(b);
                    switch(sortBy) {
                        case 'old':
                            return $a.data('created') - $b.data('created');
                        case 'name':
                            return $a.data('name').localeCompare($b.data('name'));
                        default: // recent
                            return $b.data('created') - $a.data('created');
                    }
                });
                
                // Réorganiser les cartes
                const container = $('#sujetsContainer');
                visibleCards.forEach(card => container.append(card));
                
                // Afficher message si aucun résultat
                if (visibleCards.length === 0) {
                    if (!$('#noResults').length) {
                        container.append(`
                            <div class="col-12" id="noResults">
                                <div class="text-center py-5">
                                    <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 text-muted">Aucun sujet trouvé</h5>
                                    <p class="text-muted">Essayez de modifier vos filtres</p>
                                </div>
                            </div>
                        `);
                    }
                } else {
                    $('#noResults').remove();
                }
            };

            // Événements de filtrage
            $('#filterStatus, #filterCategory, #sortBy').on('change', filterCards);
            $('#searchInput').on('input', debounce(filterCards, 300));
            
            // Fonction debounce pour la recherche
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>
@endpush
