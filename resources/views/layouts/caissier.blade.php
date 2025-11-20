<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MILA ICE CREAM - POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
</head>
<body class="h-full bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 text-white min-h-screen">
    
    @php
        $isAdminViewingCaissier = auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()
            && request()->query('userId');
    @endphp

    {{-- Header moderne avec glassmorphism - Caché sur page caisse --}}
    <div class="backdrop-blur-xl bg-white/10 border-b border-white/20 sticky top-0 z-50 shadow-2xl {{ request()->routeIs('caisse') ? 'hidden' : '' }}">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                
                {{-- Logo et titre --}}
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-2xl blur-lg opacity-50"></div>
                        <img src="{{ asset('images/logo.jpg') }}" 
                             alt="Logo Mila Ice Cream" 
                             class="relative h-12 w-12 rounded-2xl shadow-xl border-2 border-white/30"/>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-cyan-300 to-blue-300 bg-clip-text text-transparent">
                            MILA ICE CREAM
                        </h2>
                        <p class="text-xs text-cyan-200/80">Point de vente moderne</p>
                    </div>
                </div>

                {{-- Navigation Desktop --}}
                <nav class="hidden md:flex items-center gap-2 bg-white/5 backdrop-blur-sm rounded-2xl p-2 border border-white/10">
                    @if($isAdminViewingCaissier)
                        <a href="{{ route('dashboard', ['userId' => request()->query('userId')]) }}" 
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Statistiques
                        </a>
                        <a href="{{ route('mes-ventes', ['userId' => request()->query('userId')]) }}" 
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('mes-ventes') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Historique
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Statistiques
                        </a>
                        <a href="{{ route('caisse') }}" 
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('caisse') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Caisse
                        </a>
                        <a href="{{ route('mes-ventes') }}" 
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('mes-ventes') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Historique
                        </a>
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Profil
                        </a>
                    @endif
                </nav>

                {{-- Actions rapides --}}
                <div class="flex items-center gap-3">
                    {{-- Menu mobile --}}
                    <div class="md:hidden" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-all border border-white/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        {{-- Dropdown mobile avec backdrop blur --}}
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-4 top-20 bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 py-2 w-56 z-50">
                            @if($isAdminViewingCaissier)
                                <a href="{{ route('dashboard', ['userId' => request()->query('userId')]) }}" 
                                   class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/20 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Statistiques
                                </a>
                                <a href="{{ route('mes-ventes', ['userId' => request()->query('userId')]) }}" 
                                   class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/20 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Historique
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" 
                                   class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/20 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Statistiques
                                </a>
                                <a href="{{ route('caisse') }}" 
                                   class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/20 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Caisse
                                </a>
                                <a href="{{ route('mes-ventes') }}" 
                                   class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/20 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Historique
                                </a>
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center gap-3 px-4 py-3 text-white hover:bg-white/20 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Profil
                                </a>
                            @endif
                            
                            <div class="h-px bg-white/20 my-2"></div>
                            
                            @if(!$isAdminViewingCaissier)
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-3 px-4 py-3 text-red-300 hover:bg-red-500/20 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Déconnexion desktop --}}
                    @if(!$isAdminViewingCaissier)
                        <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center gap-2 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- Contenu principal --}}
    <div class="pb-24">
        {{ $slot }}
    </div>
    
    {{-- Footer moderne fixe avec glassmorphism et indicateur offline intégré --}}
<div class="fixed bottom-0 left-0 right-0 z-30 backdrop-blur-xl bg-gradient-to-r from-slate-900/95 via-blue-900/95 to-slate-900/95 border-t border-white/10 shadow-2xl">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between flex-wrap gap-3">
            {{-- Info utilisateur --}}
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg ring-2 ring-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-cyan-300 font-medium">Connecté en tant que</p>
                        <p class="font-bold text-white">{{ auth()->user()->name }}</p>
                    </div>
                </div>
                
                <div class="h-8 w-px bg-white/20"></div>
                
                <div class="px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg shadow-lg">
                    <p class="text-xs font-bold text-white">{{ strtoupper(auth()->user()->role) }}</p>
                </div>
            </div>

            {{-- Indicateurs système --}}
            <div class="hidden md:flex items-center gap-4" x-data="offlineIndicator()" x-init="init()">
                {{-- Indicateur de connexion/offline --}}
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg border transition-all"
                     :class="{
                         'bg-green-500/10 border-green-500/30': isOnline && !isSyncing,
                         'bg-red-500/10 border-red-500/30': !isOnline,
                         'bg-orange-500/10 border-orange-500/30': isSyncing
                     }">
                    <div class="relative">
                        <div class="w-2 h-2 rounded-full transition-all"
                             :class="{
                                 'bg-green-500': isOnline && !isSyncing,
                                 'bg-red-500': !isOnline,
                                 'bg-orange-500': isSyncing
                             }">
                        </div>
                        <div class="absolute inset-0 w-2 h-2 rounded-full animate-ping"
                             :class="{
                                 'bg-green-400': isOnline && !isSyncing,
                                 'bg-red-400': !isOnline,
                                 'bg-orange-400': isSyncing
                             }"
                             x-show="!isOnline || isSyncing"></div>
                    </div>
                    <span class="text-xs font-medium transition-colors"
                          :class="{
                              'text-green-400': isOnline && !isSyncing,
                              'text-red-400': !isOnline,
                              'text-orange-400': isSyncing
                          }">
                        <span x-show="isOnline && !isSyncing">Système actif</span>
                        <span x-show="!isOnline">Mode hors ligne</span>
                        <span x-show="isSyncing">Synchronisation...</span>
                    </span>
                    <span class="text-xs text-cyan-300 font-medium" x-show="pendingCount > 0">
                        (<span x-text="pendingCount"></span> en attente)
                    </span>
                </div>

                {{-- Bouton de sync manuel --}}
                <button @click="syncNow()" 
                        x-show="isOnline && pendingCount > 0"
                        x-transition
                        class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-all transform hover:scale-105 active:scale-95 shadow-lg"
                        :disabled="isSyncing">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Sync
                </button>
                
                <div class="h-6 w-px bg-white/20"></div>
                
                {{-- Horloge --}}
                <div class="flex items-center gap-2 text-cyan-300 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="current-time" class="font-medium"></span>
                </div>

                {{-- Toast de notification (position absolue au-dessus du footer) --}}
                <div x-show="showToast"
                     x-transition:enter="transform ease-out duration-300 transition"
                     x-transition:enter-start="translate-y-2 opacity-0"
                     x-transition:enter-end="translate-y-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed bottom-20 right-6 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-4 border-2 border-green-500 z-50">
                    <p class="text-sm font-semibold text-green-600 dark:text-green-400" x-text="toastMessage"></p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script horloge --}}
<script>
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit'
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }
    
    updateTime();
    setInterval(updateTime, 1000);

    // Fonction Alpine.js pour l'indicateur offline
    function offlineIndicator() {
        return {
            isOnline: navigator.onLine,
            isSyncing: false,
            pendingCount: 0,
            showToast: false,
            toastMessage: '',

            init() {
                // Écouter les changements de connexion
                window.addEventListener('online', () => this.handleOnline());
                window.addEventListener('offline', () => this.handleOffline());

                // Vérifier les ventes en attente
                this.checkPendingVentes();
                
                // Vérifier périodiquement
                setInterval(() => this.checkPendingVentes(), 30000); // Toutes les 30s
            },

            async handleOnline() {
                this.isOnline = true;
                this.showNotification('Connexion rétablie ! Synchronisation en cours...');
                
                // Attendre un peu pour stabiliser la connexion
                setTimeout(() => this.syncNow(), 2000);
            },

            handleOffline() {
                this.isOnline = false;
                this.showNotification('Mode hors ligne activé. Les ventes seront synchronisées plus tard.');
            },

            async checkPendingVentes() {
                if (!window.offlineSync) return;
                
                try {
                    const ventes = await window.offlineSync.getVentesPending();
                    this.pendingCount = ventes.length;
                } catch (error) {
                    console.error('Erreur check pending:', error);
                }
            },

            async syncNow() {
                if (!this.isOnline || this.isSyncing || !window.offlineSync) return;

                this.isSyncing = true;
                
                try {
                    const result = await window.offlineSync.synchroniserVentes();
                    
                    if (result.success && result.synced > 0) {
                        this.showNotification(`${result.synced} vente(s) synchronisée(s) avec succès !`);
                        await this.checkPendingVentes();
                        
                        // Recharger les données
                        if (window.Livewire) {
                            window.Livewire.dispatch('ventes-synchronisees');
                        }
                    }
                } catch (error) {
                    console.error('Erreur sync:', error);
                    this.showNotification('Erreur lors de la synchronisation');
                } finally {
                    this.isSyncing = false;
                }
            },

            showNotification(message) {
                this.toastMessage = message;
                this.showToast = true;
                setTimeout(() => {
                    this.showToast = false;
                }, 4000);
            }
        }
    }
</script>
</body>
</html>