@extends('backend.layouts.master')
@section('title')
    Tableau de bord
@endsection
@section('css')
    <style>
        .dashboard-card {
            transition: box-shadow 0.2s;
        }
        .dashboard-card:hover {
            box-shadow: 0 4px 18px rgba(13,110,253,0.12);
        }
        .dashboard-card .card-title {
            font-size: 0.95rem;
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
    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    @auth
                        <h4 class="fs-16 mb-1">Bonjour, <span class="text-primary">{{ Auth::user()->username }}</span></h4>
                    @endauth
                    <p class="text-muted mb-0">Suivez en temps réel l'activité et les statistiques de MaxiSujets.</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control border-0 minimal-border shadow fs-5" id="horloge" readonly>
                        <input type="text" class="form-control border-0 minimal-border shadow fs-5" id="date" readonly>
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

                            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                            document.getElementById('date').value = maintenant.toLocaleDateString('fr-FR', options);
                        }
                        setInterval(mettreAJourHorloge, 1000);
                        mettreAJourHorloge();
                    </script>
                </div>
            </div>
        </div>
    </div>

    @if ($sujetsNonApprouves > 0)
        <div class="alert alert-warning d-flex align-items-center justify-content-between mb-4">
            <div>
                <i class="ri-error-warning-line me-1"></i>
                <strong>{{ $sujetsNonApprouves }}</strong> sujet(s) en attente de modération.
            </div>
            <a href="{{ route('sujet.index', ['approuve' => 0]) }}" class="btn btn-sm btn-warning">
                Traiter maintenant <i class="ri-arrow-right-line align-bottom"></i>
            </a>
        </div>
    @endif

    <!-- Statistiques principales -->
    <div class="row mb-4 g-3">
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-success h-100">
                <div class="card-body">
                    <h5 class="card-title text-success"><i class="ri-checkbox-circle-line"></i> Approuvés</h5>
                    <a href="{{ route('sujet.index', ['approuve' => 1]) }}" class="display-6 text-success dashboard-link">
                        {{ $sujetsApprouves }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-danger h-100">
                <div class="card-body">
                    <h5 class="card-title text-danger"><i class="ri-close-circle-line"></i> En attente</h5>
                    <a href="{{ route('sujet.index', ['approuve' => 0]) }}" class="display-6 text-danger dashboard-link">
                        {{ $sujetsNonApprouves }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="ri-file-list-2-line"></i> Sujets (total)</h5>
                    <a href="{{ route('sujet.index') }}" class="display-6 text-primary dashboard-link">{{ $totalSujets }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-info h-100">
                <div class="card-body">
                    <h5 class="card-title text-info"><i class="ri-group-line"></i> Auteurs</h5>
                    <a href="{{ route('auteur.index') }}" class="display-6 text-info dashboard-link">{{ $totalAuteurs }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-warning h-100">
                <div class="card-body">
                    <h5 class="card-title text-warning"><i class="ri-folders-line"></i> Catégories</h5>
                    <a href="{{ route('categorie.index') }}" class="display-6 text-warning dashboard-link">{{ $totalCategories }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center dashboard-card border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title text-secondary"><i class="ri-download-2-line"></i> Téléchargements</h5>
                    <span class="display-6 text-secondary">{{ $totalTelechargements }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique + points -->
    <div class="row mb-4 g-3">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <i class="ri-line-chart-line"></i> Sujets publiés — 6 derniers mois
                </div>
                <div class="card-body">
                    <div id="sujetsParMoisChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center">
                    <i class="ri-star-fill text-warning" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-2">Points en circulation</h5>
                    <div class="display-6 fw-bold text-warning">{{ number_format($totalPoints) }}</div>
                    <p class="text-muted small mb-0">Cumul des soldes de points de tous les auteurs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux récents -->
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <i class="ri-file-list-2-line"></i> Derniers sujets
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0 dashboard-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Approuvé</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dernierSujets as $sujet)
                            <tr>
                                <td>{{ $sujet->libelle }}</td>
                                <td>{{ $sujet->user->username ?? '—' }}</td>
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
                                <td colspan="4" class="text-center text-muted">Aucun sujet récent</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <i class="ri-group-line"></i> Derniers auteurs inscrits
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0 dashboard-table">
                        <thead>
                            <tr>
                                <th>Nom d'utilisateur</th>
                                <th>Email</th>
                                <th>Inscrit le</th>
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
                                <td colspan="3" class="text-center text-muted">Aucun auteur récent</td>
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
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        new ApexCharts(document.querySelector("#sujetsParMoisChart"), {
            chart: { type: 'area', height: 280, toolbar: { show: false } },
            series: [{ name: 'Sujets publiés', data: @json($sujetsParMois) }],
            xaxis: { categories: @json($moisLabels) },
            colors: ['#405189'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        }).render();
    </script>
@endsection
