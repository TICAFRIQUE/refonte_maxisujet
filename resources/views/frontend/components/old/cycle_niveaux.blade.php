<style>
    .cycles-section {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 16px;
        padding: 3rem 2rem;
        margin: 3rem 0;
    }

    .cycle-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        height: 100%;
    }

    .cycle-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }

    .cycle-icon {
        font-size: 2rem;
        color: #ff6b35;
        margin-bottom: 0.5rem;
    }

    .cycle-title {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .niveau-badge {
        background: #e2e8f0;
        color: #475569;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        margin: 0.2rem;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .niveau-badge:hover {
        background: #ff6b35;
        color: white;
        border-color: #ff6b35;
        transform: scale(1.05);
    }

    .sub-niveau-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.3rem 0.7rem;
        border-radius: 16px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        margin: 0.2rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .sub-niveau-badge:hover {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #3b82f6;
    }

    .sub-niveau-badge::before {
        content: "→";
        margin-right: 0.3rem;
        opacity: 0.6;
    }

    .section-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .section-title {
        color: #1e293b;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 1.1rem;
        font-weight: 400;
    }

    @media (max-width: 768px) {
        .cycles-section {
            padding: 2rem 1rem;
            margin: 2rem 0;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
        
        .cycle-icon {
            font-size: 1.5rem;
        }
    }
</style>

<section class="container">
    <div class="cycles-section">
        <div class="section-header">
            <h2 class="section-title">Cycles & Niveaux</h2>
            <p class="section-subtitle">Parcourez nos cycles pour trouver ce qui vous intéresse</p>
        </div>
        
        <div class="row g-4">
            @foreach($data_niveaux as $cycle)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="cycle-card">
                        <div class="card-body p-4 text-center">
                            <div class="cycle-icon">
                                <i class="bi {{ $cycle->icon ?? 'bi-book' }}"></i>
                            </div>
                            <h5 class="cycle-title">{{ $cycle->libelle }}</h5>
                            
                            <div class="d-flex flex-wrap justify-content-center">
                                @foreach($cycle->children as $niveau)
                                    <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['niveau' => $niveau->slug])) }}"
                                       class="niveau-badge">
                                        {{ $niveau->libelle }}
                                    </a>
                                    
                                    @if($niveau->children && $niveau->children->count())
                                        @foreach($niveau->children as $subNiveau)
                                            <a href="{{ route('sujet.front.index', array_merge(request()->except('page'), ['niveau' => $subNiveau->slug])) }}"
                                               class="sub-niveau-badge">
                                                {{ $subNiveau->libelle }}
                                            </a>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>