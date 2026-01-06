// Animations de la navbar - MaxiSujets
document.addEventListener('DOMContentLoaded', function() {
    
    // Animation de la navbar au scroll
    const navbar = document.querySelector('.navbar');
    let lastScrollY = window.scrollY;
    let isScrolling = false;

    // Fonction pour gérer le scroll
    function handleScroll() {
        if (!isScrolling) {
            window.requestAnimationFrame(function() {
                const currentScrollY = window.scrollY;
                
                // Effet de compression/expansion
                if (currentScrollY > lastScrollY && currentScrollY > 100) {
                    // Scroll vers le bas - réduire la navbar
                    navbar.style.transform = 'translateY(-5px)';
                    navbar.style.backdropFilter = 'blur(25px)';
                    navbar.style.background = 'rgba(255, 255, 255, 0.9)';
                } else {
                    // Scroll vers le haut - restaurer la navbar
                    navbar.style.transform = 'translateY(0)';
                    navbar.style.backdropFilter = 'blur(20px)';
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                }

                // Effet de flou/netteté
                if (currentScrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }

                lastScrollY = currentScrollY;
                isScrolling = false;
            });
        }
        isScrolling = true;
    }

    // Écouter le scroll avec throttling
    let scrollTimer = null;
    window.addEventListener('scroll', function() {
        if (scrollTimer) clearTimeout(scrollTimer);
        scrollTimer = setTimeout(handleScroll, 10);
    });

    // Animation du logo au hover
    const logo = document.querySelector('.navbar-brand img');
    if (logo) {
        logo.addEventListener('mouseenter', function() {
            this.style.animation = 'logoSpin 0.6s ease-in-out';
        });

        logo.addEventListener('animationend', function() {
            this.style.animation = '';
        });
    }

    // Animation des liens avec particules
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function(e) {
            createParticle(e.target);
        });
    });

    function createParticle(element) {
        const particle = document.createElement('div');
        particle.className = 'nav-particle';
        
        const rect = element.getBoundingClientRect();
        particle.style.left = Math.random() * rect.width + 'px';
        particle.style.top = Math.random() * rect.height + 'px';
        
        element.style.position = 'relative';
        element.appendChild(particle);
        
        // Animation de la particule
        setTimeout(() => {
            particle.style.opacity = '1';
            particle.style.transform = 'scale(1) translateY(-20px)';
        }, 50);
        
        // Supprimer la particule
        setTimeout(() => {
            if (particle.parentNode) {
                particle.parentNode.removeChild(particle);
            }
        }, 800);
    }

    // Animation du menu mobile
    const mobileToggle = document.querySelector('.navbar-toggler');
    const hamburgerIcon = document.querySelector('.hamburger-icon');
    
    if (mobileToggle && hamburgerIcon) {
        let isOpen = false;
        
        mobileToggle.addEventListener('click', function() {
            isOpen = !isOpen;
            
            if (isOpen) {
                hamburgerIcon.classList.add('active');
                // Animation d'ouverture
                hamburgerIcon.querySelectorAll('span').forEach((span, index) => {
                    setTimeout(() => {
                        if (index === 0) {
                            span.style.transform = 'rotate(45deg) translate(5px, 5px)';
                        } else if (index === 1) {
                            span.style.opacity = '0';
                            span.style.transform = 'translateX(20px)';
                        } else if (index === 2) {
                            span.style.transform = 'rotate(-45deg) translate(7px, -6px)';
                        }
                    }, index * 50);
                });
            } else {
                hamburgerIcon.classList.remove('active');
                // Animation de fermeture
                hamburgerIcon.querySelectorAll('span').forEach((span, index) => {
                    setTimeout(() => {
                        span.style.transform = 'none';
                        span.style.opacity = '1';
                    }, index * 50);
                });
            }
        });
    }

    // Animation des boutons
    const navButtons = document.querySelectorAll('.navbar .btn');
    navButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.animation = 'buttonPulse 0.3s ease';
        });

        button.addEventListener('animationend', function() {
            this.style.animation = '';
        });

        // Effet de ripple au clic
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            ripple.className = 'button-ripple';
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            this.style.position = 'relative';
            this.appendChild(ripple);
            
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 600);
        });
    });

    // Animation du dropdown
    const dropdownToggle = document.querySelectorAll('.dropdown-toggle');
    dropdownToggle.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const dropdown = this.nextElementSibling;
            if (dropdown && dropdown.classList.contains('dropdown-menu')) {
                setTimeout(() => {
                    dropdown.style.animation = 'dropdownSlide 0.3s ease';
                }, 10);
            }
        });
    });

    // Effet de typing pour le brand text (optionnel)
    const brandText = document.querySelector('.brand-text');
    if (brandText && brandText.textContent.trim() === 'MaxiSujet') {
        const text = 'MaxiSujet';
        brandText.textContent = '';
        
        let i = 0;
        const typeEffect = setInterval(() => {
            if (i < text.length) {
                brandText.textContent += text.charAt(i);
                i++;
            } else {
                clearInterval(typeEffect);
                // Ajouter un curseur clignotant temporaire
                const cursor = document.createElement('span');
                cursor.textContent = '|';
                cursor.style.animation = 'blink 1s infinite';
                cursor.className = 'typing-cursor';
                brandText.appendChild(cursor);
                
                setTimeout(() => {
                    if (cursor.parentNode) {
                        cursor.parentNode.removeChild(cursor);
                    }
                }, 2000);
            }
        }, 150);
    }

    // Animation de chargement de la navbar
    navbar.style.opacity = '0';
    navbar.style.transform = 'translateY(-20px)';
    
    setTimeout(() => {
        navbar.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        navbar.style.opacity = '1';
        navbar.style.transform = 'translateY(0)';
    }, 100);

});

// Animations CSS additionnelles injectées dynamiquement
const additionalStyles = `
    <style>
        @keyframes logoSpin {
            0% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.15) rotate(10deg); }
            100% { transform: scale(1.1) rotate(5deg); }
        }

        @keyframes buttonPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1.02); }
        }

        @keyframes dropdownSlide {
            0% {
                opacity: 0;
                transform: translateY(-15px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        .nav-particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: radial-gradient(circle, #ff6b35, #f7931e);
            border-radius: 50%;
            opacity: 0;
            transform: scale(0);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            z-index: 1000;
        }

        .button-ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: rippleEffect 0.6s ease-out;
            pointer-events: none;
        }

        @keyframes rippleEffect {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }

        .navbar.scrolled {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .hamburger-icon.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px) !important;
        }

        .hamburger-icon.active span:nth-child(2) {
            opacity: 0 !important;
            transform: translateX(20px) !important;
        }

        .hamburger-icon.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px) !important;
        }

        .typing-cursor {
            color: #ff6b35;
            font-weight: normal;
        }

        /* Effets hover avancés */
        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, #ff6b35, #f7931e);
            transition: width 0.3s ease;
        }

        .navbar-brand:hover::after {
            width: 100%;
        }
    </style>
`;

// Injecter les styles
document.head.insertAdjacentHTML('beforeend', additionalStyles);