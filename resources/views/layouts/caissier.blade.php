<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLACIER POS - CAISSE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
</head>
<body class="h-full bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 text-white min-h-screen">
    
    <!-- Menu de navigation caissier -->
    @php
        // Considérer la présence de ?userId=ID comme "admin qui consulte l'historique d'un caissier".
        // Cela permet de conserver le contexte même si l'admin clique sur Statistiques (on conserve userId en query).
        $isAdminViewingCaissier = auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()
            && request()->query('userId');
    @endphp
    <div class="bg-blue-950 bg-opacity-80 border-b-2 border-cyan-400">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo Mila Ice Cream" class="h-10 w-10"/>
                    <h2 class="text-xl font-bold text-cyan-300"> MILA ICE CREAM</h2>
                    <nav class="hidden md:flex gap-2">
                        @if($isAdminViewingCaissier)
                            <a href="{{ route('dashboard', ['userId' => request()->query('userId')]) }}" 
                               class="px-4 py-2 rounded-lg font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10' }}">
                                Statistiques
                            </a>
                            <a href="{{ route('mes-ventes', ['userId' => request()->query('userId')]) }}" 
                               class="px-4 py-2 rounded-lg font-semibold transition {{ request()->routeIs('mes-ventes') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10' }}">
                                📋 Historique
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" 
                               class="px-4 py-2 rounded-lg font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10' }}">
                                Statistiques
                            </a>
                            <a href="{{ route('caisse') }}" 
                               class="px-4 py-2 rounded-lg font-semibold transition {{ request()->routeIs('caisse') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10' }}">
                                💰 Caisse
                            </a>
                            <a href="{{ route('mes-ventes') }}" 
                               class="px-4 py-2 rounded-lg font-semibold transition {{ request()->routeIs('mes-ventes') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10' }}">
                                📋 Historique
                            </a>
                            <a href="{{ route('profile.edit') }}" 
                               class="px-4 py-2 rounded-lg font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-cyan-500 text-white' : 'text-gray-300 hover:bg-white hover:bg-opacity-10' }}">
                                ⚙️ Profil
                            </a>
                        @endif
                    </nav>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Menu mobile -->
                    <div class="md:hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="text-white p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown mobile -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition
                             class="absolute right-4 top-16 bg-blue-900 rounded-lg shadow-xl border-2 border-cyan-400 py-2 w-48 z-50">
                            @if($isAdminViewingCaissier)
                                    <a href="{{ route('dashboard', ['userId' => request()->query('userId')]) }}" class="block px-4 py-2 text-white hover:bg-cyan-500 transition">
                                        Statistiques
                                    </a>
                                    <a href="{{ route('mes-ventes', ['userId' => request()->query('userId')]) }}" class="block px-4 py-2 text-white hover:bg-cyan-500 transition">
                                        📋 Historique
                                    </a>
                            @else
                                <a href="{{ route('caisse') }}" class="block px-4 py-2 text-white hover:bg-cyan-500 transition">
                                    💰 Caisse
                                </a>
                                <a href="{{ route('mes-ventes') }}" class="block px-4 py-2 text-white hover:bg-cyan-500 transition">
                                    📋 Historique
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-white hover:bg-cyan-500 transition">
                                    ⚙️ Profil
                                </a>
                            @endif
                            <hr class="border-cyan-400 my-2">
                            @if(!$isAdminViewingCaissier)
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-300 hover:bg-red-600 hover:text-white transition">
                                    🚪 Déconnexion
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Déconnexion desktop -->
                    @if(!$isAdminViewingCaissier)
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                            🚪 Déconnexion
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenu principal -->
    <div class="pb-20">
        {{ $slot }}
    </div>
    
    <!-- Pied de page fixe avec info utilisateur -->
    <div class="fixed bottom-0 left-0 right-0 bg-black bg-opacity-90 backdrop-blur-sm p-3 text-center border-t-4 border-green-500 z-30">
        <p class="text-sm md:text-lg font-bold">
            Connecté : <span class="text-green-400">{{ auth()->user()->name }}</span> 
            | Rôle : <span class="text-yellow-400">{{ strtoupper(auth()->user()->role) }}</span>
            <span class="hidden md:inline text-gray-400">
                | {{ now()->format('d/m/Y H:i') }}
            </span>
        </p>
    </div>
    
    <x-offline-indicator />
    <livewire:scripts />
</body>
</html>