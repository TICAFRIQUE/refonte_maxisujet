 <section class="container my-5">
        <h2 class="section-title">📰 Blog & Astuces</h2>
        <div class="row g-4">
            @for ($i = 1; $i <= 3; $i++)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=400&q=80"
                            class="card-img-top" alt="Blog">
                        <div class="card-body">
                            <h5 class="card-title">Astuce {{ $i }}</h5>
                            <p class="card-text">Découvrez nos conseils pour réussir vos études et optimiser votre
                                apprentissage.</p>
                            <a href="#" class="btn btn-outline-primary">Lire plus</a>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </section>