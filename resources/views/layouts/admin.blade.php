<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} - Milla Ice Cream Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slide-out {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
        
        .animate-slide-out {
            animation: slide-out 0.3s ease-out;
        }

        /* Animation pour le nom de l'entreprise */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }
            100% {
                background-position: 200% center;
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        .brand-name {
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            font-weight: 400;
            letter-spacing: 2px;
            display: flex;
            gap: 0;
            align-items: center;
            min-height: 60px;
        }
        
        .letter {
            display: inline-block;
            opacity: 0;
            background: linear-gradient(
                135deg,
                #06b6d4 0%,
                #3b82f6 50%,
                #8b5cf6 100%
            );
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 2px 4px rgba(6, 182, 212, 0.3));
        }
        
        .letter.space {
            width: 0.5em;
        }

        .brand-container {
            animation: float 3s ease-in-out infinite;
        }

        .typing-cursor {
            display: inline-block;
            width: 3px;
            height: 1.5rem;
            background: linear-gradient(180deg, #06b6d4, #3b82f6);
            margin-left: 4px;
            animation: blink 0.7s infinite;
            vertical-align: middle;
        }

        @keyframes blink {
            0%, 49% {
                opacity: 1;
            }
            50%, 100% {
                opacity: 0;
            }
        }

        .ice-cream-icon {
            animation: float 2s ease-in-out infinite;
            filter: drop-shadow(0 4px 6px rgba(6, 182, 212, 0.3));
        }

        .letter {
            display: inline-block;
            opacity: 0;
        }

        .letter.show {
            animation: fadeInUp 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-50 text-gray-800 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        @include('partials.admin.sidebar')
        
        <!-- CONTENU PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header avec breadcrumb -->
            <header class="bg-white/80 backdrop-blur-md border-b border-cyan-100 px-8 py-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $header ?? 'Dashboard' }}</h2>
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                            <i class="fas fa-home text-cyan-500"></i>
                            <span>{{ $breadcrumb ?? 'Accueil' }}</span>
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-8">
                        <!-- NOM DE L'ENTREPRISE ANIMÉ -->
                        <div class="brand-container flex items-center gap-3 px-6 py-3 bg-transparent rounded-2xl border-0 transition-all duration-700" id="brandContainer">
                            <i class="fas fa-ice-cream ice-cream-icon text-3xl text-cyan-500"></i>
                            <div class="brand-name" id="brandName"></div>
                            <span class="typing-cursor" id="cursor"></span>
                        </div>

                        <!-- ICÔNE CLOCHE NOTIFICATIONS -->
                        <livewire:admin.notification-admin />

                        <!-- Infos utilisateur -->
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Connecté en tant que</p>
                                <p class="font-semibold text-cyan-700">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-400 via-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-lg ring-4 ring-cyan-100">
                                {{ Auth::user()->initials() }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenu avec scroll -->
            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white/80 backdrop-blur-md border-t border-cyan-100 px-8 py-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-ice-cream text-cyan-500"></i>
                        &copy; {{ date('Y') }} Milla Ice Cream. Tous droits réservés.
                    </p>
                    <p class="text-gray-500">Version 1.0.0</p>
                </div>
            </footer>
        </div>
    </div>
    
    <livewire:scripts />

    <script>
        // Animation d'écriture du nom de l'entreprise
        const brandText = "Milla Ice Cream";
        const brandElement = document.getElementById('brandName');
        const cursor = document.getElementById('cursor');
        const brandContainer = document.getElementById('brandContainer');
        let letterIndex = 0;
        let isTyping = false;

        function typeWriter() {
            if (isTyping) return;
            isTyping = true;
            
            // Effacer le texte précédent et retirer l'encadrement
            brandElement.innerHTML = '';
            letterIndex = 0;
            cursor.style.display = 'inline-block';
            brandContainer.classList.remove('bg-gradient-to-r', 'from-cyan-50', 'via-blue-50', 'to-purple-50', 'border-2', 'border-cyan-200', 'shadow-lg');
            brandContainer.classList.add('bg-transparent', 'border-0');

            function addLetter() {
                if (letterIndex < brandText.length) {
                    const letter = document.createElement('span');
                    letter.className = 'letter';
                    
                    // Gérer les espaces
                    if (brandText[letterIndex] === ' ') {
                        letter.classList.add('space');
                        letter.innerHTML = '&nbsp;';
                    } else {
                        letter.textContent = brandText[letterIndex];
                    }
                    
                    brandElement.appendChild(letter);
                    
                    // Forcer le reflow pour que l'animation fonctionne
                    setTimeout(() => {
                        letter.classList.add('show');
                    }, 10);
                    
                    letterIndex++;
                    setTimeout(addLetter, 150);
                } else {
                    // Animation terminée - ajouter l'encadrement
                    setTimeout(() => {
                        cursor.style.display = 'none';
                        
                        // Ajouter l'encadrement avec animation
                        brandContainer.classList.remove('bg-transparent', 'border-0');
                        brandContainer.classList.add('bg-gradient-to-r', 'from-cyan-50', 'via-blue-50', 'to-purple-50', 'border-2', 'border-cyan-200', 'shadow-lg');
                        
                        isTyping = false;
                    }, 1000);
                }
            }

            addLetter();
        }

        // Démarrer l'animation au chargement
        window.addEventListener('load', () => {
            setTimeout(typeWriter, 500);
        });

        // Répéter l'animation toutes les 30 secondes
        setInterval(() => {
            if (!isTyping) {
                typeWriter();
            }
        }, 30000);

        // Système de toast
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', (event) => {
                console.log('Toast event received:', event);
                const data = event[0] || event;
                const type = data.type || 'success';
                const message = data.message || data;
                
                showToast(message, type);
            });

            Livewire.on('themeChanged', (event) => {
                console.log('Theme changed:', event);
                const theme = event.theme || event[0]?.theme;
                applyTheme(theme);
            });
        });

        function showToast(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500',
                warning: 'bg-yellow-500'
            };

            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                info: 'fa-info-circle',
                warning: 'fa-exclamation-triangle'
            };

            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 z-50 animate-slide-in`;
            toast.innerHTML = `
                <i class="fas ${icons[type]}"></i>
                <span>${message}</span>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('animate-slide-out');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function applyTheme(theme) {
            console.log('Applying theme:', theme);
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else if (theme === 'light') {
                document.documentElement.classList.remove('dark');
            } else if (theme === 'system') {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }
    </script>
</body>
</html>