@extends('frontend.layouts.front_app')
@section('title', $sujet->libelle . ' - ' . ($sujet->matiere->libelle ?? 'Sujet') . ' | MaxiSujets')
@section('meta_description', 'Téléchargez le sujet ' . $sujet->libelle . ' (' . ($sujet->matiere->libelle ?? '') . '). Document éducatif avec corrigé disponible.')
@section('meta_keywords', ($sujet->matiere->libelle ?? '') . ', sujet, exercice corrigé, téléchargement, ' . $sujet->libelle)
@section('og_title', $sujet->libelle . ' - ' . ($sujet->matiere->libelle ?? 'Sujet'))
@section('og_description', 'Téléchargez ce sujet' . ($sujet->matiere?->libelle ? ' de ' . $sujet->matiere->libelle : '') . ' avec corrigé.')
@section('og_image', asset('frontend/img/logo.png'))

@section('content')

    @php
        // Le libellé technique (catégorie + code) ne dit rien à l'élève : on titre avec la matière.
        $titreSujet = $sujet->matiere->libelle
            ?? ($sujet->niveaux->isNotEmpty()
                ? ($sujet->categorie->libelle ?? 'Sujet') . ' — ' . $sujet->niveaux->first()->libelle
                : ($sujet->categorie->libelle ?? $sujet->libelle));
        $userPoints = auth()->check() ? (int) auth()->user()->points : 0;

        // Les deux documents (sujet / corrigé) partagent exactement la même présentation.
        $documents = [
            [
                'type' => 'non_corrige',
                'titre' => 'Sujet',
                'label' => 'le sujet',
                'icon' => 'bi-file-earmark-text',
                'media' => $sujet->getFirstMedia('non_corrige'),
                'vide' => 'Aucun fichier disponible',
                'btn' => 'btn-warning',
            ],
            [
                'type' => 'corrige',
                'titre' => 'Corrigé',
                'label' => 'le corrigé',
                'icon' => 'bi-file-earmark-check',
                'media' => $sujet->getFirstMedia('corrige'),
                'vide' => 'Corrigé non disponible pour le moment',
                'btn' => 'btn-primary',
            ],
        ];
    @endphp

    <div class="container">
        <nav aria-label="Fil d'Ariane">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('accueil') }}"><i class="bi bi-house-door"></i> Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sujet.front.index') }}">Sujets</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $sujet->code }}</li>
            </ol>
        </nav>

        <!-- En-tête : l'essentiel du sujet en un coup d'œil -->
        <div class="page-head">
            <span class="eyebrow">{{ $sujet->categorie->libelle ?? 'Sujet' }}@if ($sujet->annee) · {{ $sujet->annee }}@endif</span>
            <h1>{{ $titreSujet }}</h1>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach ($sujet->niveaux as $niveau)
                    <a href="{{ route('sujet.front.index', ['niveau' => $niveau->slug]) }}" class="chip chip-blue text-decoration-none">
                        <i class="bi bi-mortarboard"></i>{{ $niveau->libelle }}
                    </a>
                @endforeach
                @if ($documents[1]['media'])
                    <span class="chip chip-success"><i class="bi bi-check-circle-fill"></i>Corrigé disponible</span>
                @endif
                <span class="chip" title="Code du sujet">Réf. {{ $sujet->code }}</span>
            </div>
            @if ($sujet->description)
                <p class="mt-3" style="max-width: 46rem;">{{ $sujet->description }}</p>
            @endif
        </div>

        <div class="row g-4">
            <!-- Documents : sujet et corrigé côte à côte -->
            <div class="col-lg-8">
                <div class="row g-3">
                    @foreach ($documents as $doc)
                        @php
                            $media = $doc['media'];
                            $ext = $media ? strtolower($media->extension) : null;
                            $estPdf = $ext === 'pdf';
                            $tailleMo = $media ? number_format($media->size / 1048576, 2, ',', ' ') : null;
                            $apercuUrl = $media ? route('sujet.front.apercu', ['id' => $sujet->id, 'type' => $doc['type']]) : null;
                        @endphp
                        <div class="col-md-6">
                            <section class="detail-card doc-panel" aria-labelledby="doc-{{ $doc['type'] }}">
                                <div class="doc-panel-head">
                                    <h2 id="doc-{{ $doc['type'] }}">
                                        <i class="bi {{ $doc['icon'] }}" style="color: var(--ms-blue);"></i>{{ $doc['titre'] }}
                                    </h2>
                                    @if ($media)
                                        <span class="chip">{{ strtoupper($ext) }} · {{ $tailleMo }} Mo</span>
                                    @endif
                                </div>

                                @if (!$media)
                                    <div class="doc-preview">
                                        <div class="doc-preview-inner">
                                            <i class="bi bi-file-earmark-x"></i>
                                            {{ $doc['vide'] }}
                                        </div>
                                    </div>
                                @else
                                    @auth
                                        <div class="doc-preview">
                                            @if ($estPdf)
                                                <iframe src="{{ $apercuUrl }}#toolbar=0&navpanes=0&scrollbar=0" title="Aperçu : {{ $doc['titre'] }}" loading="lazy"></iframe>
                                            @else
                                                <div class="doc-preview-inner">
                                                    <i class="bi bi-file-earmark-word" style="color: var(--ms-blue);"></i>
                                                    <p class="mb-2">Aperçu intégré indisponible pour ce format.</p>
                                                    <a href="{{ $apercuUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">Ouvrir l'aperçu</a>
                                                </div>
                                            @endif
                                        </div>

                                        @if ($userPoints > 0)
                                            <button type="button" class="btn {{ $doc['btn'] }} w-100"
                                                data-bs-toggle="modal" data-bs-target="#confirmDownloadModal"
                                                data-download-url="{{ route('sujet.front.download', ['id' => $sujet->id, 'type' => $doc['type']]) }}"
                                                data-label="{{ $doc['label'] }}">
                                                <i class="bi bi-download me-2"></i>Télécharger {{ $doc['label'] }} · 1 point
                                            </button>
                                        @else
                                            <button class="btn btn-outline-secondary w-100" disabled>
                                                <i class="bi bi-exclamation-triangle me-2"></i>Points insuffisants
                                            </button>
                                        @endif
                                    @else
                                        <button type="button" class="doc-preview" data-bs-toggle="modal" data-bs-target="#loginRequiredModal">
                                            <span class="doc-preview-inner">
                                                <i class="bi bi-eye" style="color: var(--ms-blue);"></i>
                                                <strong class="d-block" style="color: var(--ms-blue-dark);">Voir l'aperçu</strong>
                                                <small>Gratuit — connexion requise</small>
                                            </span>
                                        </button>
                                        <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-lock me-2"></i>Se connecter pour télécharger
                                        </a>
                                    @endauth
                                @endif
                            </section>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Colonne latérale : points puis informations -->
            <div class="col-lg-4">
                @auth
                    <div class="notice mb-3">
                        <div>
                            <span class="points-pill mb-2">
                                <i class="bi bi-star-fill"></i> {{ $userPoints }} point{{ $userPoints > 1 ? 's' : '' }}
                            </span>
                            <div>L'aperçu est gratuit. 1 point est déduit à chaque téléchargement.</div>
                            @if ($userPoints <= 0)
                                <a href="{{ route('user.sujet.create') }}" class="btn btn-sm btn-warning mt-2">
                                    <i class="bi bi-plus-circle me-1"></i>Publier un sujet pour gagner des points
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="notice notice-orange mb-3">
                        <div>
                            <strong class="d-block mb-1">50 points offerts à l'inscription</strong>
                            Créez un compte gratuit pour voir l'aperçu et télécharger ce sujet.
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('user.registerForm') }}" class="btn btn-warning btn-sm">Créer un compte</a>
                                <a href="{{ route('user.loginForm') }}" class="btn btn-outline-secondary btn-sm">Se connecter</a>
                            </div>
                        </div>
                    </div>
                @endauth

                <div class="detail-card p-3">
                    <h2 class="h6 mb-2">Informations</h2>
                    <dl class="spec-list">
                        <div><dt>Matière</dt><dd>{{ $sujet->matiere->libelle ?? 'Non définie' }}</dd></div>
                        <div><dt>Catégorie</dt><dd>{{ $sujet->categorie->libelle ?? 'Générale' }}</dd></div>
                        <div><dt>Niveaux</dt><dd>{{ $sujet->niveaux->pluck('libelle')->implode(', ') ?: 'Tous niveaux' }}</dd></div>
                        <div><dt>Année</dt><dd>{{ $sujet->annee ?: '—' }}</dd></div>
                        <div><dt>Publié le</dt><dd>{{ $sujet->created_at->format('d/m/Y') }}</dd></div>
                        <div><dt>Code</dt><dd>{{ $sujet->code }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sujets similaires -->
        @if ($similaires->isNotEmpty())
            <section class="mt-5" aria-labelledby="similaires-title">
                <div class="section-head">
                    <h2 id="similaires-title" class="h4">Sujets similaires</h2>
                    <a href="{{ route('sujet.front.index', array_filter(['matiere' => $sujet->matiere->slug ?? null])) }}" class="section-link">
                        Voir plus <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
                <div class="row g-3">
                    @include('frontend.pages.sujets.partials._cards', ['sujets' => $similaires])
                </div>
            </section>
        @endif

        <!-- Cycles et niveaux -->
        <section class="mt-5" aria-labelledby="niveaux-title">
            <div class="section-head">
                <h2 id="niveaux-title" class="h4">Parcourir par niveau</h2>
            </div>
            @include('frontend.components.cycle_niveaux_improved')
        </section>
    </div>

    {{-- La modale "connexion requise" est partagée, définie une seule fois dans le layout (front_app.blade.php). --}}

    <!-- Modal de confirmation de téléchargement -->
    <div class="modal fade" id="confirmDownloadModal" tabindex="-1" aria-labelledby="confirmDownloadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDownloadModalLabel"><i class="bi bi-star-fill me-2" style="color: var(--ms-orange);"></i>Confirmer le téléchargement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    @auth
                        <p class="mb-1">Télécharger <strong id="confirmDownloadLabel"></strong> coûte <strong>1 point</strong>.</p>
                        <p class="text-muted mb-0">
                            Solde actuel : <strong>{{ $userPoints }}</strong> →
                            après téléchargement : <strong>{{ max($userPoints - 1, 0) }}</strong>
                        </p>
                    @endauth
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="#" id="confirmDownloadLink" class="btn btn-warning">
                        <i class="bi bi-download me-1"></i>Confirmer
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const confirmModal = document.getElementById('confirmDownloadModal');
                if (confirmModal) {
                    confirmModal.addEventListener('show.bs.modal', function (event) {
                        const trigger = event.relatedTarget;
                        document.getElementById('confirmDownloadLink').setAttribute('href', trigger.getAttribute('data-download-url'));
                        document.getElementById('confirmDownloadLabel').textContent = trigger.getAttribute('data-label');
                    });
                }

                // Le téléchargement est un fichier (pas une navigation) : le navigateur reste
                // sur la page. On recharge juste après pour que le solde de points affiché
                // reflète le point qui vient d'être déduit côté serveur.
                const confirmLink = document.getElementById('confirmDownloadLink');
                if (confirmLink) {
                    confirmLink.addEventListener('click', function () {
                        setTimeout(() => window.location.reload(), 1200);
                    });
                }
            });
        </script>
    @endpush
@endsection
