<link rel="stylesheet" href="{{ asset('frontend/css/carousel.css') }}">
<div class="container">
    <div id="mainCarousel" class="carousel slide mt-5 pt-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            @forelse($sliders as $key => $slider)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    @if ($slider->getFirstMediaUrl('slider'))
                        <img src="{{ $slider->getFirstMediaUrl('slider') }}" class="d-block w-100 carousel-img"
                            alt="{{ $slider->titre }}" loading="{{ $key == 0 ? 'eager' : 'lazy' }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=1200&q=85"
                            class="d-block w-100 carousel-img" alt="{{ $slider->titre }}"
                            loading="{{ $key == 0 ? 'eager' : 'lazy' }}">
                    @endif
                    <div class="carousel-caption d-md-block">
                        <h3>{{ $slider->titre }}</h3>
                        @if ($slider->description)
                            <p>{{ $slider->description }}</p>
                        @endif
                        @if ($slider->bouton_text && $slider->bouton_url)
                            <a href="{{ $slider->bouton_url }}"
                                class="btn btn-primary btn-lg">{{ $slider->bouton_text }}</a>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Sliders par défaut avec des images de meilleure qualité -->
                <div class="carousel-item active">
                    <img src="{{ asset('frontend/img/slider/slide1.png') }}"
                        class="d-block w-100 carousel-img" alt="Éducation" loading="eager">
                    <div class="carousel-caption d-md-block">
                        <h3>Bienvenue sur MaxiSujets</h3>
                        <p>Plateforme de téléchargement de documents scolaires et universitaires.</p>
                        <a href="{{ route('sujet.front.index') }}" class="btn btn-primary btn-lg">Explorer les
                            cours</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('frontend/img/slider/slide2.png') }}"
                        class="d-block w-100 carousel-img" alt="Cours" loading="lazy">
                    <div class="carousel-caption d-md-block">
                        <h3>Des milliers de ressources</h3>
                        <p>Cours, devoirs, examens et concours pour tous les niveaux.</p>
                        <a href="{{ route('sujet.front.index') }}" class="btn btn-primary btn-lg">Découvrir</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('frontend/img/slider/slide3.png') }}"
                        class="d-block w-100 carousel-img" alt="Réussite" loading="lazy">
                    <div class="carousel-caption d-md-block">
                        <h3>Réussissez vos études</h3>
                        <p>Accédez facilement aux documents et astuces pour progresser.</p>
                        <a href="{{ route('sujet.front.index') }}" class="btn btn-primary btn-lg">Commencer</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('frontend/img/slider/slide2.png') }}"
                        class="d-block w-100 carousel-img" alt="Collaboration" loading="lazy">
                    <div class="carousel-caption d-md-block">
                        <h3>Partagez vos connaissances</h3>
                        <p>Contribuez à la communauté en partageant vos documents.</p>
                        <a href="{{ route('user.sujet.create') }}" class="btn btn-primary btn-lg">Partager</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('frontend/img/slider/slide1.png') }}"
                        class="d-block w-100 carousel-img" alt="Excellence" loading="lazy">
                    <div class="carousel-caption d-md-block">
                        <h3>Excellence académique</h3>
                        <p>Des ressources de qualité pour votre réussite scolaire et universitaire.</p>
                        <a href="{{ route('user.register') }}" class="btn btn-primary btn-lg">Rejoindre</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Contrôles du carousel -->
        @if ($sliders->count() > 1 || $sliders->count() == 0)
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </button>
        @endif

        <!-- Indicateurs -->
        @if ($sliders->count() > 1)
            <div class="carousel-indicators">
                @foreach ($sliders as $key => $slider)
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="{{ $key }}"
                        class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            </div>
        @elseif($sliders->count() == 0)
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3"
                    aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="4"
                    aria-label="Slide 5"></button>
            </div>
        @endif
    </div>
</div>
