<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Milla Ice Cream - Système de Gestion Premium</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="logo-container">
                <div class="logo-wrapper">
                    <!-- Option 1: Utilisez votre logo personnalisé -->
                    <img src="{{ asset('images/logo.jpg') }}" alt="Glacier Pro Logo" class="logo-image">
                </div>
                <span class="logo-text">Milla Ice Cream</span>
            </div>
        
        <nav class="nav-links">
            <a href="#features" class="nav-link">Fonctionnalités</a>
            <a href="#produits" class="nav-link">Produits</a>
            <a href="#contact" class="nav-link">Contact</a>
        </nav>
        
        <a href="{{ route('login') }}" class="login-btn">
            <i class="fas fa-sign-in-alt"></i>
            Se connecter
        </a>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>

        <div class="hero-content">
            <div class="hero-text">
                <div class="badge-tag">
                    <i class="fas fa-sparkles"></i>
                    Nouveau système de gestion
                </div>
                
                <h1 class="hero-title">
                    Transformez votre Glacier avec notre Solution Premium
                </h1>
                
                <p class="hero-subtitle">
                    Une plateforme complète et intuitive pour gérer vos ventes, 
                    optimiser vos stocks et analyser vos performances en temps réel.
                </p>
                
                <div class="cta-buttons">
                    <a href="#" class="btn-primary">
                        Commencer maintenant
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn-secondary">
                        <i class="fas fa-play-circle"></i>
                        Découvrir les fonctionnalités
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Utilisateurs actifs</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">99.9%</span>
                        <span class="stat-label">Disponibilité</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Support client</span>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="dashboard-preview">
                    <div class="preview-header">
                        <div class="preview-dot"></div>
                        <div class="preview-dot"></div>
                        <div class="preview-dot"></div>
                    </div>
                    
                    <div class="preview-content">
                        <div class="metric-card">
                            <div class="metric-info">
                                <h4>Ventes aujourd'hui</h4>
                                <div class="metric-value">247</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>

                        <div class="metric-card">
                            <div class="metric-info">
                                <h4>Chiffre d'affaires</h4>
                                <div class="metric-value">1.2M</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>

                        <div class="metric-card">
                            <div class="metric-info">
                                <h4>Clients satisfaits</h4>
                                <div class="metric-value">98%</div>
                            </div>
                            <div class="metric-icon">
                                <i class="fas fa-smile"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="section-header reveal">
            <span class="section-badge">
                <i class="fas fa-bolt"></i>
                Fonctionnalités puissantes
            </span>
            <h2 class="section-title">Tout ce dont vous avez besoin pour réussir</h2>
            <p class="section-description">
                Des outils professionnels conçus pour optimiser chaque aspect de votre activité
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Gestion Multi-utilisateurs</h3>
                <p>
                    Gérez facilement vos équipes avec des rôles et permissions personnalisés. 
                    Administrateurs et caissiers travaillent en harmonie.
                </p>
            </div>

            <div class="feature-card reveal">
                <div class="feature-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3>Analyses Avancées</h3>
                <p>
                    Visualisez vos performances avec des tableaux de bord intuitifs. 
                    Prenez des décisions éclairées grâce aux données en temps réel.
                </p>
            </div>

            <div class="feature-card reveal">
                <div class="feature-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3>Gestion des Stocks</h3>
                <p>
                    Suivez vos inventaires avec précision. Recevez des alertes automatiques 
                    pour les réapprovisionnements nécessaires.
                </p>
            </div>

            <div class="feature-card reveal">
                <div class="feature-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h3>Point de Vente Moderne</h3>
                <p>
                    Interface de caisse rapide et intuitive. Traitez les transactions 
                    en quelques secondes avec un système optimisé.
                </p>
            </div>

            <div class="feature-card reveal">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>100% Responsive</h3>
                <p>
                    Accédez à votre système depuis n'importe quel appareil. 
                    Gérez votre business où que vous soyez.
                </p>
            </div>

            <div class="feature-card reveal">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Sécurité Renforcée</h3>
                <p>
                    Vos données sont protégées avec un cryptage de niveau bancaire. 
                    Sauvegardes automatiques et protection contre les pertes.
                </p>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="produits">
        <div class="section-header reveal">
            <span class="section-badge">
                <i class="fas fa-ice-cream"></i>
                Nos produits vedettes
            </span>
            <h2 class="section-title">Les favoris de nos clients</h2>
            <p class="section-description">
                Découvrez notre sélection de glaces artisanales préparées avec passion
            </p>
        </div>

        <div class="products-grid">
            <div class="product-card reveal">
                <div class="product-image">
                    <div class="product-badge">
                        ⭐ Best-seller
                    </div>
                    🍦
                </div>
                <div class="product-content">
                    <h3 class="product-name">Vanille Premium</h3>
                    <p class="product-description">
                        Notre classique intemporel préparé avec de la vanille de Madagascar. 
                        Crémeux et authentique.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">3,500 F</span>
                        <button class="add-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="product-card reveal">
                <div class="product-image">
                    <div class="product-badge">
                        🔥 Tendance
                    </div>
                    🍫
                </div>
                <div class="product-content">
                    <h3 class="product-name">Chocolat Intense</h3>
                    <p class="product-description">
                        Chocolat noir 70% de cacao pour les amateurs de saveurs intenses. 
                        Un vrai délice gourmand.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">4,000 F</span>
                        <button class="add-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="product-card reveal">
                <div class="product-image">
                    <div class="product-badge">
                        ✨ Nouveau
                    </div>
                    🍓
                </div>
                <div class="product-content">
                    <h3 class="product-name">Fraise Fraîche</h3>
                    <p class="product-description">
                        Fraises locales cueillies à maturité. Une explosion de fraîcheur 
                        à chaque bouchée.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">3,800 F</span>
                        <button class="add-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="product-card reveal">
                <div class="product-image">
                    🥭
                </div>
                <div class="product-content">
                    <h3 class="product-name">Mangue Exotique</h3>
                    <p class="product-description">
                        Mangues tropicales juteuses et sucrées. Un voyage gustatif 
                        sous les tropiques.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">4,200 F</span>
                        <button class="add-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="product-card reveal">
                <div class="product-image">
                    🥥
                </div>
                <div class="product-content">
                    <h3 class="product-name">Coco Paradis</h3>
                    <p class="product-description">
                        Noix de coco fraîche râpée dans une crème onctueuse. 
                        Rafraîchissant et léger.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">3,900 F</span>
                        <button class="add-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="product-card reveal">
                <div class="product-image">
                    🍋
                </div>
                <div class="product-content">
                    <h3 class="product-name">Citron Givré</h3>
                    <p class="product-description">
                        Sorbet au citron acidulé et rafraîchissant. Parfait pour 
                        les journées ensoleillées.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">3,500 F</span>
                        <button class="add-btn">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="section-header reveal">
            <span class="section-badge" style="background: rgba(255, 255, 255, 0.1); color: var(--primary);">
                <i class="fas fa-envelope"></i>
                Contactez-nous
            </span>
            <h2 class="section-title">Nous sommes là pour vous</h2>
            <p class="section-description" style="color: rgba(255, 255, 255, 0.7);">
                Une question ? Un projet ? Notre équipe est à votre écoute
            </p>
        </div>

        <div class="contact-grid">
            <div class="contact-card reveal">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Visitez-nous</h3>
                <p class="contact-info">
                    Secteur 15, Karpala<br>
                    Ouagadougou, Burkina Faso<br>
                    BP 1234
                </p>
                <a href="#" class="contact-link">
                    Obtenir l'itinéraire
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="contact-card reveal">
                <div class="contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3>Appelez-nous</h3>
                <p class="contact-info">
                    <strong>Standard:</strong><br>
                    +226 25 12 34 56
                </p>
                <a href="https://wa.me/22625123456" target="_blank" class="whatsapp-btn">
                    <i class="fab fa-whatsapp"></i>
                    Discuter sur WhatsApp
                </a>
            </div>

            <div class="contact-card reveal">
                <div class="contact-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Horaires</h3>
                <p class="contact-info">
                    <strong>Lundi - Vendredi</strong><br>
                    09:00 - 20:00<br><br>
                    <strong>Samedi - Dimanche</strong><br>
                    10:00 - 22:00
                </p>
                <a href="#" class="contact-link">
                    Prendre rendez-vous
                    <i class="fas fa-calendar-alt"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="logo-container">
                <div class="logo-wrapper">
                    <!-- Option 1: Utilisez votre logo personnalisé -->
                    <img src="{{ asset('images/logo.jpg') }}" alt="Glacier Pro Logo" class="logo-image">
                </div>
                <span class="logo-text">Milla Ice Cream</span>
            </div>

            <div class="footer-links">
                <a href="#" class="footer-link">Accueil</a>
                <a href="#features" class="footer-link">Fonctionnalités</a>
                <a href="#produits" class="footer-link">Produits</a>
                <a href="#contact" class="footer-link">Contact</a>
                <a href="#" class="footer-link">Confidentialité</a>
                <a href="#" class="footer-link">Conditions</a>
            </div>

            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <p>&copy; {{'Y'}} Milla Ice Cream - Système de Gestion Premium</p>
                <p style="margin-top: 0.5rem; font-size: 0.9rem;">
                    Développé avec <i class="fas fa-heart" style="color: #f43f5e;"></i> au Burkina Faso
                </p>
                <p style="margin-top: 0.5rem; font-size: 0.85rem; opacity: 0.6;">
                    Développé par Junior OUEDRAOGO & Josias DJIOLGOU
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Scroll reveal animation
        const revealElements = document.querySelectorAll('.reveal');
        
        const reveal = () => {
            revealElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.classList.add('active');
                }
            });
        };

        window.addEventListener('scroll', reveal);
        reveal(); // Initial check

        // Smooth scroll for anchor links
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

        // Add to cart animation
        document.querySelectorAll('.add-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.innerHTML = '<i class="fas fa-check"></i>';
                this.style.background = '#10b981';
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-plus"></i>';
                    this.style.background = '';
                }, 2000);
            });
        });
    </script>
</body>
</html>