<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion • Milla Ice Cream</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* { font-family: 'Inter', sans-serif; }
body {
    background: linear-gradient(135deg, #06b6d4 0%, #0284c7 50%, #0369a1 100%);
    position: relative;
    overflow: hidden;
}
/* Bulles (inchangées) */
.bubble {
    position: absolute; border-radius: 50%; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); animation: float 15s infinite ease-in-out;
}
.bubble:nth-child(1) { width: 120px; height: 120px; top: 10%; left: 15%; animation-delay: 0s; }
.bubble:nth-child(2) { width: 80px; height: 80px; top: 60%; left: 80%; animation-delay: 2s; }
.bubble:nth-child(3) { width: 150px; height: 150px; top: 70%; left: 10%; animation-delay: 4s; }
.bubble:nth-child(4) { width: 100px; height: 100px; top: 20%; left: 75%; animation-delay: 1s; }
@keyframes float {
    0%, 100% { transform: translateY(0) translateX(0) scale(1); opacity: 0.7; }
    25% { transform: translateY(-30px) translateX(20px) scale(1.1); opacity: 0.4; }
    50% { transform: translateY(-60px) translateX(-20px) scale(0.9); opacity: 0.6; }
    75% { transform: translateY(-30px) translateX(30px) scale(1.05); opacity: 0.5; }
}
@keyframes slideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.animate-slide-in { animation: slideIn 0.6s ease-out; }
@keyframes shine { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
.shine-effect { position: relative; overflow: hidden; }
.shine-effect::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); animation: shine 3s infinite;
}
@keyframes pulse-glow {
    0%, 100% { filter: drop-shadow(0 0 15px rgba(6, 182, 212, 0.5)); } 50% { filter: drop-shadow(0 0 30px rgba(6, 182, 212, 0.8)); }
}
.pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
.input-focus { transition: all 0.3s ease; }
.input-focus:focus { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(6, 182, 212, 0.3); }
@keyframes drip { 0% { transform: translateY(0) scaleY(1); opacity: 1; } 100% { transform: translateY(10px) scaleY(1.1); opacity: 0.7; } }
.drip-effect { animation: drip 2s ease-in-out infinite alternate; }

/* === SEULE PARTIE MODIFIÉE POUR RÉDUIRE LA HAUTEUR === */
.grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 480px !important;   /* était 600px → réduit de 120px */
    max-width: 920px;               /* était 1000px */
    width: 92%;
    margin: auto;
}
.right-column {
    background-image: linear-gradient(to bottom right, #06b6d4, #0369a1);
    padding: 2rem 1.5rem !important;   /* p-10 → beaucoup plus compact */
}
.right-column h1 { font-size: 3.5rem !important; margin-bottom: 0.5rem !important; } /* était 6xl */
.right-column .w-32 { width: 6.5rem !important; height: 6.5rem !important; } /* logo plus petit */
.right-column p.text-xl { font-size: 1.1rem !important; padding-top: 0.75rem !important; }
.right-column .mt-10 { margin-top: 1.5rem !important; }
.right-column .p-5 { padding: 1rem !important; }

/* Padding gauche réduit aussi */
.left-column-padding { padding: 2.5rem 2rem !important; } /* nouveau */

@media (max-width: 768px) {
    .grid-container { grid-template-columns: 1fr; min-height: auto !important; }
    .right-column { display: none; }
    .left-column-padding { padding: 2rem 1.5rem !important; }
}
</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative"> <!-- p-8 → p-4 -->
<div class="bubble"></div><div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>

<div class="relative z-10 animate-slide-in grid-container bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/20">

    <!-- === COLONNE GAUCHE (formulaire) === -->
    <div class="left-column-padding flex flex-col justify-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-sign-in-alt text-cyan-500 mr-2"></i> Connexion
        </h2>

        @if ($errors->any())
        <div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-center text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i> Identifiants incorrects.
        </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf
            <!-- Email OU Pseudo -->
<div class="group">
    <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="email">
        <i class="fas fa-user text-cyan-500 mr-2"></i> Email ou Pseudo
    </label>
    <div class="relative">
        <input type="text" name="email" id="email" required autofocus autocomplete="username" value="{{ old('email') }}"
               class="input-focus w-full px-5 py-3 pl-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all text-sm"
               placeholder="admin@millaicecream.com ou admin">
        <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
    </div>
</div>

            <!-- Mot de passe -->
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="password">
                    <i class="fas fa-lock text-cyan-500 mr-2"></i> Mot de passe
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                           class="input-focus w-full px-5 py-3 pl-12 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all text-sm"
                           placeholder="••••••••••">
                    <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-cyan-500 transition">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-5 h-5 text-cyan-600 rounded-lg focus:ring-cyan-500">
                    <span class="text-gray-600">Se souvenir de moi</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-cyan-600 hover:text-cyan-700 font-semibold hover:underline">
                    Mot de passe oublié ?
                </a>
                @endif
            </div>

            <button type="submit" class="group relative w-full py-3.5 mt-5 bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-600 hover:from-cyan-600 hover:via-blue-600 hover:to-indigo-700 text-white font-bold text-lg rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <span class="relative z-10 flex items-center justify-center gap-3">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                    <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500">
            <p>© {{ date('Y') }} Milla Ice Cream • Tous droits réservés</p>
        </div>
    </div>

    <!-- === COLONNE DROITE (inchangée dans le comportement, juste plus compacte) === -->
    <div class="right-column relative flex flex-col justify-center items-center text-center shine-effect">
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <svg class="w-full h-full" viewBox="0 0 400 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="30" fill="white" class="drip-effect"/>
                <circle cx="350" cy="150" r="40" fill="white" class="drip-effect" style="animation-delay: 0.5s"/>
                <circle cx="200" cy="30" r="25" fill="white" class="drip-effect" style="animation-delay: 1s"/>
            </svg>
        </div>

        <div class="relative pulse-glow mb-4">
            <a href="{{ route('home') }}">
                <div class="w-32 h-32 mx-auto bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border-4 border-white/30 overflow-hidden hover:border-white/70 transition duration-300">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo Milla Ice Cream" class="w-full h-full object-cover p-2 rounded-full">
                </div>
            </a>
        </div>

        <h1 class="text-6xl font-extrabold text-white mb-2 tracking-tight drop-shadow-lg">Milla</h1>
        <h1 class="text-6xl font-extrabold text-white mb-6 tracking-tight drop-shadow-lg">Ice Cream</h1>
        <p class="text-cyan-50 text-xl font-medium border-t border-white/30 pt-3">Espace Administration</p>

        <div class="mt-6 bg-white/10 rounded-xl p-4 border border-white/30 text-left w-full max-w-xs text-sm">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-white text-xl mt-1"></i>
                <div>
                    <h4 class="font-semibold text-white mb-1">Accès sécurisé</h4>
                    <p class="text-cyan-50">Réservé au personnel autorisé de Milla Ice Cream.</p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-4 right-4">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-xs font-medium text-gray-700 shadow-lg">
                <i class="fas fa-code text-cyan-500"></i> Version 1.0.0
            </span>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye'); eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash'); eyeIcon.classList.add('fa-eye');
    }
}
window.addEventListener('load', () => {
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);
});
</script>
</body>
</html>