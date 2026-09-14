@extends('frontend.layouts.front_app')

@section('title', 'Contact - ' . ($parametre?->nom_projet ?? 'MaxiSujets'))
@section('meta_description', 'Contactez ' . ($parametre?->nom_projet ?? 'MaxiSujets') . ' : adresse, téléphone, email et localisation sur la carte.')

@section('content')

    @php
        $contactNom = $parametre?->nom_projet ?? 'MaxiSujets';
        $contactDescription = $parametre?->description_projet
            ?? "Ce site regroupe de nombreux supports de sujets et de cours portant sur divers domaines de votre parcours scolaire et universitaire. Ces sujets et cours que vous pourrez trouver ici sont tous à télécharger avec des jetons et sous divers formats : doc, ppt, pdf, rar, zip.";
        $contactHoraires = $parametre?->horaires ?? "Du lundi au samedi (8H-18H)";
        $contactAdresse = $parametre?->localisation ?? "Côte D'Ivoire, Abidjan, Cocody II Plateaux";
        $contactSiegeSocial = $parametre?->siege_social;
        $contactTel1 = $parametre?->contact1;
        $contactTel2 = $parametre?->contact2;
        $contactTel3 = $parametre?->contact3;
        $contactFax = $parametre?->fax;
        $contactEmail1 = $parametre?->email1 ?? 'info@maxisujets.net';
        $contactEmail2 = $parametre?->email2;

        // Construit l'URL d'intégration de la carte à partir du champ admin "google_maps" :
        // celui-ci peut contenir un code <iframe> collé tel quel, une simple URL Google Maps,
        // ou être vide (dans ce cas on géocode directement l'adresse/localisation, sans clé API).
        $mapEmbedUrl = null;
        $rawMap = trim((string) ($parametre?->google_maps ?? ''));
        if ($rawMap !== '') {
            if (str_contains($rawMap, '<iframe')) {
                preg_match('/src=["\']([^"\']+)["\']/i', $rawMap, $matches);
                $mapEmbedUrl = $matches[1] ?? null;
            } elseif (str_starts_with($rawMap, 'http')) {
                $mapEmbedUrl = $rawMap;
            }
        }
        if (!$mapEmbedUrl) {
            $mapEmbedUrl = 'https://www.google.com/maps?q=' . urlencode($contactAdresse) . '&output=embed';
        }

        $whatsappNumber = $contactTel1 ? preg_replace('/\D+/', '', $contactTel1) : null;
    @endphp

    @push('styles')
        <style>
            .contact-info-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); border: none; }
            .contact-info-item { display: flex; align-items: flex-start; gap: 0.9rem; padding: 1rem 0; border-bottom: 1px solid var(--ms-border-subtle); }
            .contact-info-item:last-child { border-bottom: none; }
            .contact-info-icon {
                width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
                display: inline-flex; align-items: center; justify-content: center;
                background: var(--ms-orange-light); color: var(--ms-orange-dark); font-size: 1.05rem;
            }
            .contact-map-wrap { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); min-height: 320px; }
            .contact-map-wrap iframe { width: 100%; height: 100%; min-height: 320px; border: 0; display: block; }
            .contact-cta-btn { display: inline-flex; align-items: center; gap: 0.5rem; }
        </style>
    @endpush

    <div class="container">
        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            @include('frontend.components.retour')
            <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
                <ol class="breadcrumb bg-light rounded p-3">
                    <li class="breadcrumb-item"><a href="{{ route('accueil') }}" class="text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>

        <div class="mb-4">
            <h1 class="fw-bold mb-1 fs-3">Contactez {{ $contactNom }}</h1>
            <p class="text-muted mb-0">{{ $contactDescription }}</p>
        </div>

        <div class="row g-4">
            <!-- Coordonnées -->
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <div class="card-body p-4">
                        <div class="contact-info-item">
                            <span class="contact-info-icon"><i class="bi bi-clock-fill"></i></span>
                            <div>
                                <strong class="d-block">Horaires</strong>
                                <span class="text-muted">{{ $contactHoraires }}</span>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <span class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></span>
                            <div>
                                <strong class="d-block">Adresse</strong>
                                <span class="text-muted">{{ $contactAdresse }}</span>
                                @if ($contactSiegeSocial)
                                    <br><span class="text-muted small">Siège social : {{ $contactSiegeSocial }}</span>
                                @endif
                            </div>
                        </div>

                        @if ($contactTel1 || $contactTel2 || $contactTel3)
                            <div class="contact-info-item">
                                <span class="contact-info-icon"><i class="bi bi-telephone-fill"></i></span>
                                <div>
                                    <strong class="d-block">Téléphone</strong>
                                    @foreach (array_filter([$contactTel1, $contactTel2, $contactTel3]) as $tel)
                                        <a href="tel:{{ preg_replace('/\s+/', '', $tel) }}" class="d-block text-muted text-decoration-none">{{ $tel }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($contactFax)
                            <div class="contact-info-item">
                                <span class="contact-info-icon"><i class="bi bi-printer-fill"></i></span>
                                <div>
                                    <strong class="d-block">Fax</strong>
                                    <span class="text-muted">{{ $contactFax }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="contact-info-item">
                            <span class="contact-info-icon"><i class="bi bi-envelope-fill"></i></span>
                            <div>
                                <strong class="d-block">Email</strong>
                                <a href="mailto:{{ $contactEmail1 }}" class="d-block text-muted text-decoration-none">{{ $contactEmail1 }}</a>
                                @if ($contactEmail2)
                                    <a href="mailto:{{ $contactEmail2 }}" class="d-block text-muted text-decoration-none">{{ $contactEmail2 }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="mailto:{{ $contactEmail1 }}" class="btn btn-warning contact-cta-btn">
                                <i class="bi bi-envelope"></i> Nous écrire
                            </a>
                            @if ($whatsappNumber)
                                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener" class="btn btn-outline-success contact-cta-btn">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte Google Maps -->
            <div class="col-lg-7">
                <div class="contact-map-wrap h-100">
                    <iframe src="{{ $mapEmbedUrl }}" allowfullscreen loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" title="Localisation {{ $contactNom }}"></iframe>
                </div>
            </div>
        </div>
    </div>

@endsection
