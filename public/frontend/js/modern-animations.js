// Animations et interactions avancées pour MaxiSujets
document.addEventListener('DOMContentLoaded', function() {
    
    // Animation d'apparition progressive des éléments
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Appliquer l'animation aux cartes de documents
    document.querySelectorAll('.document-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        card.style.transitionDelay = `${index * 0.1}s`;
        observer.observe(card);
    });
    
    // Animation des statistiques avec compteurs
    function animateCounters() {
        const counters = document.querySelectorAll('.stats-number');
        counters.forEach(counter => {
            const text = counter.textContent;
            const number = parseInt(text.replace(/\D/g, ''));
            const suffix = text.replace(/\d/g, '');
            let current = 0;
            
            const increment = number / 50; // Animation en 50 étapes
            
            const updateCounter = () => {
                if (current < number) {
                    current += increment;
                    counter.textContent = Math.floor(current) + suffix;
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = number + suffix;
                }
            };
            
            // Observer pour déclencher l'animation
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(updateCounter, Math.random() * 500); // Décalage aléatoire
                        counterObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.7 });
            
            counterObserver.observe(counter);
        });
    }
    
    // Lancer l'animation des compteurs
    animateCounters();
    
    // Effet de parallax pour le hero
    let ticking = false;
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.modern-hero');
        
        if (hero) {
            const speed = 0.3;
            hero.style.transform = `translateY(${scrolled * speed}px)`;
        }
        
        ticking = false;
    }
    
    function requestParallaxUpdate() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }
    
    window.addEventListener('scroll', requestParallaxUpdate);
    
    // Animation des cartes au hover
    document.querySelectorAll('.document-card, .feature-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Animation de loading pour les boutons
    document.querySelectorAll('.modern-btn, .btn').forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.href && !this.href.includes('#')) {
                // Créer un effet de ripple
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255,255,255,0.6);
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            }
        });
    });
    
    // Animation pour les badges
    document.querySelectorAll('.category-badge, .level-badge, .subject-badge').forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Smooth scroll pour les liens internes
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Animation pour la barre de recherche dans le hero
    const searchInput = document.querySelector('.search-section input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.parentElement.style.transition = 'transform 0.3s ease';
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.parentElement.style.transform = 'scale(1)';
        });
    }
    
    // Ajouter du CSS pour l'effet ripple
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .document-preview {
            transition: all 0.3s ease;
        }
        
        .document-card:hover .document-preview {
            transform: scale(1.05);
        }
        
        /* Animation pour les éléments fade-in */
        .fade-in, .fade-in-delay-1, .fade-in-delay-2, .fade-in-delay-3 {
            opacity: 0;
            transform: translateY(20px);
        }
        
        /* Scroll reveal pour mobile */
        @media (max-width: 768px) {
            .document-card {
                margin-bottom: 1rem;
            }
            
            .modern-hero {
                min-height: 70vh;
                padding: 2rem 0;
            }
            
            .search-section {
                margin-top: 1rem;
                padding: 1.5rem;
            }
        }
        
        /* Animation de chargement */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    `;
    document.head.appendChild(style);
    
    // Déclencher les animations fade-in pour le hero
    setTimeout(() => {
        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    }, 100);
    
    setTimeout(() => {
        document.querySelectorAll('.fade-in-delay-1').forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    }, 300);
    
    setTimeout(() => {
        document.querySelectorAll('.fade-in-delay-2').forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    }, 500);
    
    setTimeout(() => {
        document.querySelectorAll('.fade-in-delay-3').forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    }, 700);
    
});

// Fonction utilitaire pour les notifications toast
window.showSuccessToast = function(message) {
    // Simple toast notification sans dépendance externe
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 9999;
        font-weight: 500;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.style.transform = 'translateX(0)', 100);
    setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};