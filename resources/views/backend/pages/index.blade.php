@extends('backend.layouts.master')
@section('title')
    Tableau de bord
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .dashboard-card {
            transition: box-shadow 0.2s;
        }
        .dashboard-card:hover {
            box-shadow: 0 4px 18px rgba(13,110,253,0.12);
        }
        .dashboard-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .dashboard-card .display-6 {
            font-size: 2.2rem;
            font-weight: 700;
        }
        .dashboard-link {
            text-decoration: none;
        }
        .dashboard-link:hover {
            text-decoration: underline;
        }
        .dashboard-table th, .dashboard-table td {
            vertical-align: middle !important;
        }
        .dashboard-table th {
            background: #f6f8fa;
        }
        .dashboard-table tr:hover {
            background: #f0f4ff;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                               @auth
                               <h4 class="fs-16 mb-1">👋 Bonjour, <span class="text-primary">{{ Auth::user()->username }}</span> !</h4>
                               @endauth
                                <p class="text-muted mb-0">Suivez en temps réel l’activité et les statistiques de votre plateforme MaxiSujets.</p>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <form action="javascript:void(0);">
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-auto">
                                            <div class="input-group input-group-lg">
                                                <input type="text"
                                                    class="form-control border-0 minimal-border shadow fs-5" id="horloge"
                                                    readonly>
                                                <input type="text"
                                                    class="form-control border-0 minimal-border shadow fs-5" id="date"
                                                    readonly>
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-time-line me-2"></i>
                                                    <i class="ri-calendar-line"></i>
                                                </div>
                                            </div>
                                            <script>
                                                function mettreAJourHorloge() {
                                                    var maintenant = new Date();
                                                    var heures = maintenant.getHours().toString().padStart(2, '0');
                                                    var minutes = maintenant.getMinutes().toString().padStart(2, '0');
                                                    var secondes = maintenant.getSeconds().toString().padStart(2, '0');
                                                    document.getElementById('horloge').value = heures + ':' + minutes + ':' + secondes;

                                                    var options = {
                                                        weekday: 'long',
                                                        year: 'numeric',
                                                        month: 'long',
                                                        day: 'numeric'
                                                    };
                                                    var dateEnFrancais = maintenant.toLocaleDateString('fr-FR', options);
                                                    document.getElementById('date').value = dateEnFrancais;
                                                }

                                                setInterval(mettreAJourHorloge, 1000);
                                                mettreAJourHorloge(); // Appel initial pour afficher l'heure et la date immédiatement
                                            </script>
                                        </div>
                                        <!--end col-->

                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->


            </div> <!-- end .h-100-->

        </div> <!-- end col -->


    </div>

    <!-- Statistiques principales -->
    <div class="row mb-4 g-3">
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-success">
                <div class="card-body">
                    <h5 class="card-title text-success"><i class="ri-checkbox-circle-line"></i> Sujets approuvés</h5>
                    <a href="{{ route('sujet.index', ['approuve' => 1]) }}" class="display-6 text-success dashboard-link">
                        {{ $sujetsApprouves }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-danger">
                <div class="card-body">
                    <h5 class="card-title text-danger"><i class="ri-close-circle-line"></i> Sujets non approuvés</h5>
                    <a href="{{ route('sujet.index', ['approuve' => 0]) }}" class="display-6 text-danger dashboard-link">
                        {{ $sujetsNonApprouves }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="ri-file-list-2-line"></i> Sujets (total)</h5>
                    <span class="display-6 text-primary">{{ $totalSujets }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-info">
                <div class="card-body">
                    <h5 class="card-title text-info"><i class="ri-user-3-line"></i> Utilisateurs</h5>
                    <span class="display-6 text-info">{{ $totalUsers }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning"><i class="ri-folders-line"></i> Catégories</h5>
                    <span class="display-6 text-warning">{{ $totalCategories }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-secondary">
                <div class="card-body">
                    <h5 class="card-title text-secondary"><i class="ri-download-2-line"></i> Téléchargements</h5>
                    <span class="display-6 text-secondary">{{ $totalTelechargements }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux récents -->
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <i class="ri-file-list-2-line"></i> Derniers Sujets
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0 dashboard-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Approuvé</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dernierSujets as $sujet)
                            <tr>
                                <td>{{ $sujet->libelle }}</td>
                                <td>
                                    @if($sujet->approuve)
                                        <span class="badge bg-success">Oui</span>
                                    @else
                                        <span class="badge bg-danger">Non</span>
                                    @endif
                                </td>
                                <td>{{ $sujet->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun sujet récent</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <i class="ri-user-3-line"></i> Derniers Utilisateurs
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0 dashboard-table">
                        <thead>
                            <tr>
                                <th>Nom d'utilisateur</th>
                                <th>Email</th>
                                <th>Date d'inscription</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dernierUsers as $user)
                            <tr>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun utilisateur récent</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
