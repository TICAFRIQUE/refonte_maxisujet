<!-- Matières en carrousel avec design moderne -->
<div class="matieres-carousel-container position-relative">
    <div class="carousel-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="bi bi-collection text-primary me-2"></i>
            Explorez nos Matières
        </h3>

        <div class="carousel-controls d-flex gap-2">
            <button class="carousel-btn carousel-prev" onclick="prevSlide('matieres-carousel')" disabled aria-label="Matières précédentes">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="carousel-btn carousel-next" onclick="nextSlide('matieres-carousel')" aria-label="Matières suivantes">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="carousel-wrapper">
        <div class="matieres-carousel" id="matieres-carousel">
            @foreach ($data_matieres as $index => $matiere)
                <div class="carousel-slide {{ $index < 4 ? 'active' : '' }}">
                    <div class="feature-card text-center h-100">
                        <div class="mb-3">
                            @php
                                // Icône adaptée à la matière ; couleur cyclée sur la palette de marque
                                // (orange / bleu / navy) plutôt qu'un arc-en-ciel déconnecté du logo.
                                $matiereIcons = [
                                    'mathématiques' => 'bi-calculator',
                                    'physique' => 'bi-lightning',
                                    'chimie' => 'bi-droplet',
                                    'français' => 'bi-book',
                                    'anglais' => 'bi-globe',
                                    'histoire' => 'bi-clock-history',
                                    'géographie' => 'bi-globe-americas',
                                    'svt' => 'bi-tree',
                                    'sciences' => 'bi-atom',
                                    'informatique' => 'bi-laptop',
                                ];

                                $slug = strtolower($matiere->libelle);
                                $icon = 'bi-book';
                                foreach ($matiereIcons as $key => $iconName) {
                                    if (str_contains($slug, $key)) {
                                        $icon = $iconName;
                                        break;
                                    }
                                }

                                $palette = [
                                    ['solid' => 'var(--ms-orange)', 'light' => 'var(--ms-orange-light)'],
                                    ['solid' => 'var(--ms-blue)', 'light' => 'var(--ms-blue-light)'],
                                    ['solid' => 'var(--ms-navy)', 'light' => 'rgba(30, 58, 138, 0.1)'],
                                ];
                                $tone = $palette[$index % 3];
                            @endphp

                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle icon-container"
                                style="width: 80px; height: 80px; background: {{ $tone['light'] }};">
                                <i class="bi {{ $icon }} display-5" style="color: {{ $tone['solid'] }};"></i>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3" style="color: var(--ms-ink);">{{ $matiere->libelle }}</h5>

                        <p class="text-muted mb-4">
                            Découvrez les ressources en {{ strtolower($matiere->libelle) }} pour tous les niveaux
                            d'étude.
                        </p>

                        <a href="{{ route('sujet.front.index', ['matiere' => $matiere->slug]) }}"
                            class="btn px-4"
                            style="background: {{ $tone['solid'] }}; color: white; border: none;">
                            <i class="bi bi-arrow-right me-2"></i>Explorer
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Indicateurs de pagination -->
    @if ($data_matieres->count() > 4)
        <div class="carousel-indicators text-center mt-4">
            @php
                $totalSlides = ceil($data_matieres->count() / 4);
            @endphp
            @for ($i = 0; $i < $totalSlides; $i++)
                <button class="carousel-indicator {{ $i === 0 ? 'active' : '' }}"
                    onclick="goToSlide('matieres-carousel', {{ $i }})" data-slide="{{ $i }}">
                </button>
            @endfor
        </div>
    @endif
</div>

@if ($data_matieres->count() > 8)
    <div class="text-center mt-5">
        <a href="{{ route('sujet.front.index') }}" class="modern-btn">
            <i class="bi bi-grid me-2"></i>Voir Toutes les Matières
        </a>
    </div>
@endif

<style>
    .matieres-carousel-container {
        margin: 2rem 0;
    }

    .carousel-wrapper {
        overflow: hidden;
        position: relative;
    }

    .matieres-carousel {
        display: flex;
        transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        gap: 1.5rem;
        padding: 0.5rem 0;
    }

    .carousel-slide {
        flex: 0 0 calc(25% - 1.125rem);
        opacity: 0.7;
        transform: scale(0.95);
        transition: all 0.3s ease;
    }

    .carousel-slide.active {
        opacity: 1;
        transform: scale(1);
    }

    .carousel-slide .feature-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .carousel-slide .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .carousel-slide .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--ms-flag-stripe);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .carousel-slide .feature-card:hover::before {
        opacity: 1;
    }

    .icon-container {
        transition: transform 0.3s ease;
    }

    .carousel-slide:hover .icon-container {
        transform: scale(1.1) rotate(5deg);
    }

    .carousel-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: 1.5px solid var(--ms-blue-light);
        background: white;
        color: var(--ms-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: var(--ms-shadow-rest);
    }

    .carousel-btn:hover {
        background: var(--ms-blue);
        color: white;
    }

    .carousel-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .carousel-btn:disabled:hover {
        background: white;
        color: var(--ms-blue);
    }

    .carousel-indicators {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .carousel-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #ddd;
        background: transparent;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-indicator.active {
        background: var(--ms-blue);
        border-color: var(--ms-blue);
        transform: scale(1.2);
    }

    .carousel-indicator:hover {
        border-color: var(--ms-blue);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .carousel-slide {
            flex: 0 0 calc(33.333% - 1rem);
        }
    }

    @media (max-width: 992px) {
        .carousel-slide {
            flex: 0 0 calc(50% - 0.75rem);
        }

        .carousel-slide .feature-card {
            padding: 1.5rem 1rem;
        }
    }

    @media (max-width: 768px) {
        .carousel-slide {
            flex: 0 0 calc(100% - 0.5rem);
        }

        .carousel-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .matieres-carousel {
            gap: 1rem;
        }
    }

    /* Animation d'entrée */
    .carousel-slide {
        animation: fadeInUp 0.6s ease forwards;
    }

    .carousel-slide:nth-child(1) {
        animation-delay: 0.1s;
    }

    .carousel-slide:nth-child(2) {
        animation-delay: 0.2s;
    }

    .carousel-slide:nth-child(3) {
        animation-delay: 0.3s;
    }

    .carousel-slide:nth-child(4) {
        animation-delay: 0.4s;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }

        to {
            opacity: 0.7;
            transform: translateY(0) scale(0.95);
        }
    }

    .carousel-slide.active {
        animation: fadeInActive 0.6s ease forwards;
    }

    @keyframes fadeInActive {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>

<script>
    let currentSlideIndex = 0;
    let isAnimating = false;
    const slidesPerView = window.innerWidth < 768 ? 1 : window.innerWidth < 992 ? 2 : window.innerWidth < 1200 ? 3 : 4;

    function updateCarousel(carouselId) {
        if (isAnimating) return;

        const carousel = document.getElementById(carouselId);
        const slides = carousel.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;
        const maxSlideIndex = Math.max(0, totalSlides - slidesPerView);

        // Ajuster l'index si nécessaire
        currentSlideIndex = Math.min(Math.max(0, currentSlideIndex), maxSlideIndex);

        // Appliquer la transformation
        const translateX = -(currentSlideIndex * (100 / slidesPerView));
        carousel.style.transform = `translateX(${translateX}%)`;

        // Mettre à jour les classes active
        slides.forEach((slide, index) => {
            slide.classList.remove('active');
            if (index >= currentSlideIndex && index < currentSlideIndex + slidesPerView) {
                slide.classList.add('active');
            }
        });

        // Mettre à jour les boutons
        updateNavigationButtons(carouselId, currentSlideIndex, maxSlideIndex);

        // Mettre à jour les indicateurs
        updateIndicators(carouselId);

        isAnimating = true;
        setTimeout(() => isAnimating = false, 500);
    }

    function nextSlide(carouselId) {
        const carousel = document.getElementById(carouselId);
        const slides = carousel.querySelectorAll('.carousel-slide');
        const maxSlideIndex = Math.max(0, slides.length - slidesPerView);

        if (currentSlideIndex < maxSlideIndex) {
            currentSlideIndex++;
            updateCarousel(carouselId);
        }
    }

    function prevSlide(carouselId) {
        if (currentSlideIndex > 0) {
            currentSlideIndex--;
            updateCarousel(carouselId);
        }
    }

    function goToSlide(carouselId, slideIndex) {
        const carousel = document.getElementById(carouselId);
        const slides = carousel.querySelectorAll('.carousel-slide');
        const maxSlideIndex = Math.max(0, slides.length - slidesPerView);

        currentSlideIndex = Math.min(slideIndex * slidesPerView, maxSlideIndex);
        updateCarousel(carouselId);
    }

    function updateNavigationButtons(carouselId, currentIndex, maxIndex) {
        const prevBtn = document.querySelector('.carousel-prev');
        const nextBtn = document.querySelector('.carousel-next');

        if (prevBtn) {
            prevBtn.disabled = currentIndex <= 0;
        }

        if (nextBtn) {
            nextBtn.disabled = currentIndex >= maxIndex;
        }
    }

    function updateIndicators(carouselId) {
        const indicators = document.querySelectorAll('.carousel-indicator');
        const currentPage = Math.floor(currentSlideIndex / slidesPerView);

        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentPage);
        });
    }

    // Auto-slide functionality (optionnel)
    let autoSlideInterval;

    function startAutoSlide(carouselId, interval = 5000) {
        autoSlideInterval = setInterval(() => {
            const carousel = document.getElementById(carouselId);
            const slides = carousel.querySelectorAll('.carousel-slide');
            const maxSlideIndex = Math.max(0, slides.length - slidesPerView);

            if (currentSlideIndex >= maxSlideIndex) {
                currentSlideIndex = 0;
            } else {
                currentSlideIndex++;
            }
            updateCarousel(carouselId);
        }, interval);
    }

    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    }

    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        updateCarousel('matieres-carousel');

        // Démarrer l'auto-slide (optionnel - décommenter si souhaité)
        // startAutoSlide('matieres-carousel', 4000);

        // Pause auto-slide au hover
        const carouselContainer = document.querySelector('.matieres-carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoSlide);
            carouselContainer.addEventListener('mouseleave', () => {
                // startAutoSlide('matieres-carousel', 4000);
            });
        }
    });

    // Responsive handling
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const newSlidesPerView = window.innerWidth < 768 ? 1 : window.innerWidth < 992 ? 2 : window
                .innerWidth < 1200 ? 3 : 4;
            if (newSlidesPerView !== slidesPerView) {
                window.slidesPerView = newSlidesPerView;
                currentSlideIndex = 0;
                updateCarousel('matieres-carousel');
            }
        }, 250);
    });
</script>

