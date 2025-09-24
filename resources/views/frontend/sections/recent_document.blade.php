<section class="container my-5">
    <h2 class="section-title">📚 Derniers documents</h2>
    <div class="row g-4">
        @foreach($sujetsRecents as $sujet)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="row g-0 align-items-center">
                        <div class="col-4 text-center">
                            @php
                                $preview = $sujet->getFirstMediaUrl('non_corrige');
                                $isPdf = $preview && Str::endsWith($preview, '.pdf');
                            @endphp
                            <div class="p-2">
                                @if($isPdf)
                                    <img src="{{ asset('frontend/img/pdf-icon.png') }}" alt="PDF"
                                         class="img-fluid rounded" style="max-height:90px; object-fit:cover; border:1px solid #eee;">
                                @elseif($preview)
                                    <img src="{{ $preview }}" alt="Aperçu"
                                         class="img-fluid rounded" style="max-height:90px; object-fit:cover; border:1px solid #eee;">
                                @else
                                    <img src="{{ asset('frontend/img/file-placeholder.png') }}" alt="Aperçu"
                                         class="img-fluid rounded" style="max-height:90px; object-fit:cover; border:1px solid #eee;">
                                @endif
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card-body py-3 px-2">
                                <h6 class="card-title text-primary mb-1 d-flex align-items-center">
                                    {{ $sujet->libelle }}
                                    <span class="badge bg-dark ms-2">{{ $sujet->code }}</span>
                                </h6>
                                <p class="card-text small mb-2" style="min-height:38px;">{{ Str::limit($sujet->description, 60) }}</p>
                                <div class="mb-2">
                                    <span class="badge bg-info">{{ $sujet->matiere->libelle ?? '' }}</span>
                                    @foreach($sujet->niveaux as $niveau)
                                        <span class="badge bg-secondary">{{ $niveau->libelle }}</span>
                                    @endforeach
                                    <span class="badge bg-warning text-dark">{{ $sujet->annee }}</span>
                                    <span class="badge bg-success">{{ $sujet->categorie->libelle ?? '' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted small">
                                        <i class="bi bi-calendar"></i>
                                        Publié le {{ $sujet->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('sujet.front.show', $sujet->libelle) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Détails
                                    </a>
                                    @auth
                                        @if(auth()->user()->points > 0)
                                            @if($sujet->getFirstMediaUrl('non_corrige'))
                                                <a href="{{ $sujet->getFirstMediaUrl('non_corrige') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                                    <i class="bi bi-download"></i> Sujet
                                                </a>
                                            @endif
                                            @if($sujet->getFirstMediaUrl('corrige'))
                                                <a href="{{ $sujet->getFirstMediaUrl('corrige') }}" class="btn btn-outline-success btn-sm" target="_blank">
                                                    <i class="bi bi-download"></i> Corrigé
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('user.sujet.create') }}" class="btn btn-outline-danger btn-sm">
                                               <small> <i class="bi bi-exclamation-triangle"></i> Points insuffisants pour télécharger</small>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-lock"></i> Connectez-vous pour télécharger
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('sujet.front.index') }}" class="btn btn-primary px-4">
            <i class="bi bi-list"></i> Voir tous les sujets
        </a>
    </div>
</section>
