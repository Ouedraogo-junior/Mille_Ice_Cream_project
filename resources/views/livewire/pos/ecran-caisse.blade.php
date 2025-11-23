<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 pb-24 relative overflow-hidden">
    {{-- Animated background elements --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    {{-- Messages Flash avec animation moderne --}}
    @if($messageSucces)
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-init="setTimeout(() => { show = false; $wire.effacerMessages() }, 3000)"
             class="fixed top-4 right-4 z-50 bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-sm border border-white/20">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center animate-bounce">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="font-semibold">{{ $messageSucces }}</span>
            </div>
        </div>
    @endif

    @if($messageErreur)
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-init="setTimeout(() => { show = false; $wire.effacerMessages() }, 4000)"
             class="fixed top-4 right-4 z-50 bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-sm border border-white/20">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <span class="font-semibold">{{ $messageErreur }}</span>
            </div>
        </div>
    @endif

    {{-- Modal de confirmation moderne avec effet glassmorphism --}}
    @if($showConfirmation)
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50 p-4">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-white/95 backdrop-blur-xl rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl border border-white/20">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-emerald-400 to-green-600 mb-6 animate-bounce shadow-lg shadow-green-500/50">
                        <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold mb-2 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Vente réussie !</h3>
                    <p class="text-gray-600 mb-4 text-sm font-medium">Ticket N° <span class="font-bold text-purple-600">{{ $derniereVenteId }}</span></p>
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl p-6 mb-6 shadow-lg">
                        <p class="text-white text-4xl font-bold animate-pulse">{{ number_format($this->totalPanier, 0, ',', ' ') }} <span class="text-2xl">FCFA</span></p>
                    </div>
                    
                    <div class="flex gap-3">
                        <button wire:click="reimprimerTicket" 
                                class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-4 rounded-xl font-semibold transition-all transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Réimprimer
                        </button>
                        <button wire:click="nouvelleVente" 
                                class="flex-1 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-6 py-4 rounded-xl font-semibold transition-all transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouvelle vente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Header moderne avec glassmorphism --}}
    <div class="sticky top-0 z-50 backdrop-blur-xl bg-white/10 border-b border-white/20 shadow-lg mb-8">
        <div class="container mx-auto px-4 py-5">
            <div class="flex items-center justify-between gap-6">
                <div class="flex items-center gap-3" x-data="{ open: false }">
    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
        <span class="text-2xl">🍦</span>
    </div>
    <div class="relative">
        <button @click="open = !open" class="flex items-center gap-2 group">
            <div>
                <h1 class="text-2xl font-bold text-white group-hover:text-purple-300 transition">Point de Vente</h1>
                <p class="text-xs text-purple-300">Système moderne de caisse</p>
            </div>
            <svg class="w-5 h-5 text-white transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        
        <!-- Dropdown Menu -->
        <div x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute left-0 top-full mt-2 w-64 bg-white/10 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 py-2 z-50">
            
            <a href="{{ route('caisse') }}" 
               class="flex items-center gap-3 px-4 py-3 hover:bg-white/20 transition-all group {{ request()->routeIs('caisse') ? 'bg-white/15' : '' }}">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-white group-hover:text-purple-300">Caisse</p>
                    <p class="text-xs text-purple-300">Enregistrer des ventes</p>
                </div>
            </a>

            <a href="{{ route('mes-ventes') }}" 
               class="flex items-center gap-3 px-4 py-3 hover:bg-white/20 transition-all group {{ request()->routeIs('mes-ventes') ? 'bg-white/15' : '' }}">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-white group-hover:text-purple-300">Historique</p>
                    <p class="text-xs text-purple-300">Mes ventes passées</p>
                </div>
            </a>

            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 hover:bg-white/20 transition-all group {{ request()->routeIs('dashboard') ? 'bg-white/15' : '' }}">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-white group-hover:text-purple-300">Statistiques</p>
                    <p class="text-xs text-purple-300">Performances & rapports</p>
                </div>
            </a>

            <div class="h-px bg-white/20 my-2"></div>

            <a href="{{ route('profile.edit') }}" 
               class="flex items-center gap-3 px-4 py-3 hover:bg-white/20 transition-all group">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-white group-hover:text-purple-300">Profil</p>
                    <p class="text-xs text-purple-300">Paramètres du compte</p>
                </div>
            </a>
        </div>
    </div>
</div>
                
                {{-- Barre de recherche moderne --}}
                <div class="flex-1 max-w-xl relative group">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-purple-300 group-focus-within:text-purple-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="recherche"
                           placeholder="Rechercher un produit..."
                           class="w-full pl-12 pr-4 py-3.5 rounded-xl bg-white/10 backdrop-blur-sm text-white placeholder-purple-300 border border-white/20 focus:outline-none focus:border-purple-400 focus:bg-white/20 transition-all shadow-lg focus:shadow-xl">
                </div>

                {{-- Indicateur panier moderne --}}
                <div class="relative">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all transform hover:scale-105 cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                @if($this->nombreArticles > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-pulse">{{ $this->nombreArticles }}</span>
                                @endif
                            </div>
                            <span class="text-white font-semibold">{{ $this->nombreArticles }} article(s)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        {{-- Layout principal --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- SECTION PRODUITS --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Filtres par catégorie avec effet moderne et scroll --}}
                {{-- Filtres par catégorie avec icônes --}}
<div class="relative group/scroll">
    {{-- Bouton scroll gauche --}}
    <button onclick="scrollCategories('left')" 
            id="scroll-left"
            class="hidden absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full shadow-lg items-center justify-center transition-all transform hover:scale-110 active:scale-95 group-hover/scroll:flex">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    
    {{-- Conteneur des catégories scrollable --}}
    <div id="categories-container" class="flex gap-3 overflow-x-auto pb-2 scroll-smooth categories-scroll px-12">
        {{-- Bouton "Tous" --}}
        <button wire:click="filtrerCategorie(null)" 
                class="group relative px-6 py-3.5 rounded-xl font-semibold whitespace-nowrap transition-all duration-300 transform hover:scale-105 {{ $categorieSelectionnee === null ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg shadow-purple-500/50' : 'bg-white/10 backdrop-blur-sm text-purple-200 hover:bg-white/20 border border-white/20' }}">
            <span class="flex items-center gap-2">
                <span class="text-xl">🍨</span>
                Tous
            </span>
            @if($categorieSelectionnee === null)
                <div class="absolute inset-0 rounded-xl bg-white opacity-20 blur-xl"></div>
            @endif
        </button>

        {{-- Catégories avec icônes --}}
        @foreach($this->categories as $categorie)
            <button wire:click="filtrerCategorie({{ $categorie->id }})" 
                    class="group relative px-6 py-3.5 rounded-xl font-semibold whitespace-nowrap transition-all duration-300 transform hover:scale-105 {{ $categorieSelectionnee === $categorie->id ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg shadow-purple-500/50' : 'bg-white/10 backdrop-blur-sm text-purple-200 hover:bg-white/20 border border-white/20' }}">
                <span class="flex items-center gap-2">
                    {{-- Afficher l'icône de la catégorie ou icône par défaut --}}
                    @if($categorie->icone)
                        @if(str_starts_with($categorie->icone, 'http') || str_starts_with($categorie->icone, '/'))
                            {{-- Si c'est une URL ou un chemin d'image --}}
                            <img src="{{ $categorie->icone }}" 
                                 alt="{{ $categorie->nom }}" 
                                 class="w-5 h-5 object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                            <span class="text-xl hidden">🏷️</span>
                        @else
                            {{-- Si c'est un emoji ou texte --}}
                            <span class="text-xl">{{ $categorie->icone }}</span>
                        @endif
                    @else
                        {{-- Icône par défaut selon la catégorie --}}
                        <span class="text-xl">
                            @switch(strtolower($categorie->nom))
                                @case('glace')
                                @case('glaces')
                                    🍦
                                    @break
                                @case('boisson')
                                @case('boissons')
                                    🥤
                                    @break
                                @case('dessert')
                                @case('desserts')
                                    🍰
                                    @break
                                @case('snack')
                                @case('snacks')
                                    🍿
                                    @break
                                @default
                                    🏷️
                            @endswitch
                        </span>
                    @endif
                    {{ $categorie->nom }}
                </span>
                @if($categorieSelectionnee === $categorie->id)
                    <div class="absolute inset-0 rounded-xl bg-white opacity-20 blur-xl"></div>
                @endif
            </button>
        @endforeach
    </div>
    
    {{-- Bouton scroll droite --}}
    <button onclick="scrollCategories('right')" 
            id="scroll-right"
            class="hidden absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full shadow-lg items-center justify-center transition-all transform hover:scale-110 active:scale-95 group-hover/scroll:flex">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
        </svg>
    </button>
    
    {{-- Indicateurs de gradient aux extrémités --}}
    <div class="absolute left-0 top-0 bottom-2 w-12 bg-gradient-to-r from-slate-900 to-transparent pointer-events-none"></div>
    <div class="absolute right-0 top-0 bottom-2 w-12 bg-gradient-to-l from-slate-900 to-transparent pointer-events-none"></div>
</div>

{{-- Grille de produits avec images --}}
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5 mt-6">
    @forelse($this->produits as $produit)
        @php $variant = $produit->variants->first(); @endphp
        <button wire:click="ajouterAuPanier({{ $produit->id }})"
                wire:loading.attr="disabled"
                wire:target="ajouterAuPanier({{ $produit->id }})"
                class="group relative bg-white/10 backdrop-blur-md rounded-2xl p-5 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-purple-400/50 hover:shadow-2xl hover:shadow-purple-500/20 transform hover:-translate-y-2 {{ (optional($variant)->stock !== null && optional($variant)->stock <= 0) ? 'opacity-50 cursor-not-allowed' : '' }}">
            
            {{-- Effet de brillance au survol --}}
            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-white/0 via-white/5 to-white/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
{{-- Badge stock amélioré avec gestion stock illimité --}}
@php
    $gererStock = $variant->gerer_stock ?? false;
    $seuilAlerte = $variant->seuil_alerte ?? 10; // Valeur par défaut si null
@endphp

@if($gererStock)
    {{-- Stock géré : afficher les badges selon le niveau --}}
    @if($variant->stock <= 0)
        {{-- Stock épuisé --}}
        <span class="absolute top-3 right-3 bg-gradient-to-r from-gray-700 to-gray-900 text-white text-xs px-3 py-1.5 rounded-full font-bold shadow-lg z-10 flex items-center gap-1">
            <i class="fas fa-ban"></i>
            Épuisé
        </span>
    @elseif($variant->stock <= $seuilAlerte)
        {{-- Stock faible (alerte) --}}
        <span class="absolute top-3 right-3 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs px-3 py-1.5 rounded-full font-bold shadow-lg z-10 animate-pulse flex items-center gap-1">
            <i class="fas fa-exclamation-triangle"></i>
            {{ $variant->stock }} restant{{ $variant->stock > 1 ? 's' : '' }}
        </span>
    @elseif($variant->stock <= ($seuilAlerte * 2))
        {{-- Stock moyen --}}
        <span class="absolute top-3 right-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs px-3 py-1.5 rounded-full font-bold shadow-lg z-10 flex items-center gap-1">
            <i class="fas fa-box"></i>
            {{ $variant->stock }} en stock
        </span>
    @else
        {{-- Stock bon --}}
        <span class="absolute top-3 right-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white text-xs px-3 py-1.5 rounded-full font-bold shadow-lg z-10 flex items-center gap-1">
            <i class="fas fa-check-circle"></i>
            {{ $variant->stock }} disponible{{ $variant->stock > 1 ? 's' : '' }}
        </span>
    @endif
{{-- @else
    
    <span class="absolute top-3 right-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs px-3 py-1.5 rounded-full font-bold shadow-lg z-10 flex items-center gap-1.5">
        <i class="fas fa-infinity"></i>
        Ilimité
    </span> --}}
@endif

            {{-- Image produit avec gestion d'erreur --}}
            <div class="relative aspect-square bg-gradient-to-br from-purple-400 via-pink-400 to-purple-500 rounded-xl mb-4 flex items-center justify-center text-5xl overflow-hidden shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                @if($produit->image)
                    <img src="{{ asset('storage/' . $produit->image) }}" 
                         alt="{{ $produit->nom }}" 
                         class="w-full h-full object-cover rounded-xl transform group-hover:scale-110 transition-transform duration-500"
                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    {{-- Image par défaut si erreur de chargement --}}
                    <div class="hidden w-full h-full items-center justify-center transform group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500">
                        <span class="text-6xl">
                            @if($produit->categorie)
                                @switch(strtolower($produit->categorie->nom))
                                    @case('glace')
                                    @case('glaces')
                                        🍦
                                        @break
                                    @case('boisson')
                                    @case('boissons')
                                        🥤
                                        @break
                                    @case('dessert')
                                    @case('desserts')
                                        🍰
                                        @break
                                    @case('snack')
                                    @case('snacks')
                                        🍿
                                        @break
                                    @default
                                        🍽️
                                @endswitch
                            @else
                                🍽️
                            @endif
                        </span>
                    </div>
                @else
                    {{-- Pas d'image : afficher emoji par défaut --}}
                    <span class="transform group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 text-6xl">
                        @if($produit->categorie)
                            @switch(strtolower($produit->categorie->nom))
                                @case('glace')
                                @case('glaces')
                                    🍦
                                    @break
                                @case('boisson')
                                @case('boissons')
                                    🥤
                                    @break
                                @case('dessert')
                                @case('desserts')
                                    🍰
                                    @break
                                @case('snack')
                                @case('snacks')
                                    🍿
                                    @break
                                @default
                                    🍽️
                            @endswitch
                        @else
                            🍽️
                        @endif
                    </span>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>

           {{-- Infos produit --}}
<div class="relative z-10">
    <h3 class="font-bold text-base text-white mb-2 group-hover:text-purple-300 transition line-clamp-2">{{ $produit->nom }}</h3>
    <div class="flex items-center justify-between">
        <p class="text-2xl font-bold bg-gradient-to-r from-purple-300 to-pink-300 bg-clip-text text-transparent">{{ number_format(optional($variant)->prix ?? 0, 0, ',', ' ') }}</p>
        <span class="text-xs text-purple-300 font-semibold">FCFA</span>
    </div>
    
    {{-- AJOUTE CETTE NOUVELLE SECTION ICI --}}
    {{-- Barre de stock visuelle --}}
{{-- Affichage du stock pour une variante de produit --}}
@php
    $gererStock = $variant->gerer_stock ?? false;
    $seuilAlerte = $variant->seuil_alerte ?? 10; // Valeur par défaut si null
@endphp

@if($gererStock)
    {{-- Stock géré : afficher le stock avec barre de progression --}}
    <div class="mt-3 space-y-1">
        <div class="flex items-center justify-between text-xs">
            <span class="text-purple-300 font-medium flex items-center gap-1">
                <i class="fas fa-box text-xs"></i>
                Stock
            </span>
            <span class="font-bold {{ $variant->stock == 0 ? 'text-red-400 animate-pulse' : ($variant->stock <= $seuilAlerte ? 'text-red-400 animate-pulse' : ($variant->stock <= ($seuilAlerte * 2) ? 'text-yellow-400' : 'text-emerald-400')) }}">
                {{ $variant->stock }}
            </span>
        </div>
        
        {{-- Barre de progression --}}
        @php
            // Calculer le pourcentage basé sur 3x le seuil d'alerte comme "bon stock"
            $maxStock = $seuilAlerte * 3; // Ex: seuil = 5 → max = 15 pour 100%
            $percentage = $maxStock > 0 ? min(100, ($variant->stock / $maxStock) * 100) : 0;
        @endphp
        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500 {{ $variant->stock == 0 ? 'bg-gray-500' : ($variant->stock <= $seuilAlerte ? 'bg-gradient-to-r from-red-500 to-rose-600' : ($variant->stock <= ($seuilAlerte * 2) ? 'bg-gradient-to-r from-yellow-500 to-orange-500' : 'bg-gradient-to-r from-emerald-500 to-green-600')) }}"
                 style="width: {{ $percentage }}%">
            </div>
        </div>
        
        {{-- Alerte stock épuisé --}}
        @if($variant->stock == 0)
            <div class="flex items-center gap-1 text-xs text-red-400 font-semibold mt-1">
                <i class="fas fa-ban"></i>
                <span>Épuisé</span>
            </div>
        @elseif($variant->stock <= $seuilAlerte)
            <div class="flex items-center gap-1 text-xs text-orange-400 font-semibold mt-1 animate-pulse">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Stock faible (seuil: {{ $seuilAlerte }})</span>
            </div>
        @endif
    </div>
@else
    {{-- Stock illimité : afficher l'icône infini --}}
    <div class="mt-3">
        <div class="flex items-center justify-between px-3 py-2 bg-green-500/20 rounded-lg border border-green-500/30">
            <span class="text-green-300 font-medium flex items-center gap-2 text-xs">
                <i class="fas fa-infinity text-sm"></i>
                Disponible
            </span>
            <span class="text-green-400 font-bold text-xs">
                <i class="fas fa-check-circle"></i>
            </span>
        </div>
    </div>
@endif

            {{-- Loading indicator --}}
            <div wire:loading wire:target="ajouterAuPanier({{ $produit->id }})" 
                 class="absolute inset-0 bg-black/70 backdrop-blur-sm rounded-2xl flex items-center justify-center z-20">
                <div class="relative">
                    <div class="w-12 h-12 border-4 border-purple-400/30 border-t-purple-400 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 w-12 h-12 border-4 border-transparent border-t-pink-400 rounded-full animate-spin" style="animation-duration: 1.5s; animation-direction: reverse;"></div>
                </div>
            </div>

            {{-- Bouton d'ajout au survol --}}
            <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-2 rounded-lg shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
        </button>
    @empty
        <div class="col-span-full text-center py-20">
            <div class="bg-white/5 backdrop-blur-sm rounded-3xl p-12 border border-white/10">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-purple-200 mb-2">Aucun produit trouvé</p>
                <p class="text-purple-300">Essayez de modifier vos critères de recherche</p>
            </div>
        </div>
    @endforelse
</div>
            </div>

            {{-- SECTION PANIER --}}
            <div class="lg:col-span-1">
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 sticky top-28 border border-white/20 shadow-2xl">
                    
                    {{-- Header panier --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/20">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Panier</h2>
                    </div>

                    {{-- Liste des articles avec scroll personnalisé --}}
                    <div class="space-y-3 mb-6 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($panier as $cle => $item)
                            <div class="group bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 hover:border-purple-400/50 transition-all duration-300">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-semibold text-white flex-1 text-sm">{{ $item['nom'] }}</h4>
                                    <button wire:click="retirerDuPanier('{{ $cle }}')" 
                                            class="ml-2 p-1.5 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white transition-all transform hover:scale-110 active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="modifierQuantite('{{ $cle }}', {{ $item['quantite'] - 1 }})" 
                                                class="w-8 h-8 rounded-lg bg-red-500/80 hover:bg-red-500 text-white font-bold transition-all transform hover:scale-110 active:scale-95 shadow-lg">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/>
                                            </svg>
                                        </button>
                                        <span class="text-white font-bold text-lg w-12 text-center bg-white/10 rounded-lg py-1">{{ $item['quantite'] }}</span>
                                        <button wire:click="modifierQuantite('{{ $cle }}', {{ $item['quantite'] + 1 }})" 
                                                class="w-8 h-8 rounded-lg bg-emerald-500/80 hover:bg-emerald-500 text-white font-bold transition-all transform hover:scale-110 active:scale-95 shadow-lg">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="text-purple-300 font-bold text-sm">{{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }} F</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <div class="w-20 h-20 mx-auto mb-4 bg-white/5 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-purple-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <p class="text-purple-300 font-medium">Panier vide</p>
                                <p class="text-purple-400 text-sm mt-1">Ajoutez des produits pour commencer</p>
                            </div>
                        @endforelse
                    </div>

                    @if(!empty($panier))
                        {{-- Mode de paiement moderne --}}
                        <div class="mb-6 bg-white/5 rounded-2xl p-4 border border-white/10">
                            <label class="block text-sm font-semibold mb-3 text-purple-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Mode de paiement
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                <button wire:click="$set('modePaiement', 'espece')" 
                                        class="group relative px-3 py-3 rounded-xl font-semibold text-xs transition-all transform hover:scale-105 {{ $modePaiement === 'espece' ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg' : 'bg-white/10 text-purple-200 hover:bg-white/20 border border-white/20' }}">
                                    <div class="flex flex-col items-center gap-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span>Espèce</span>
                                    </div>
                                </button>
                                <button wire:click="$set('modePaiement', 'mobile')" 
                                        class="group relative px-3 py-3 rounded-xl font-semibold text-xs transition-all transform hover:scale-105 {{ $modePaiement === 'mobile' ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg' : 'bg-white/10 text-purple-200 hover:bg-white/20 border border-white/20' }}">
                                    <div class="flex flex-col items-center gap-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Mobile</span>
                                    </div>
                                </button>
                                <button wire:click="$set('modePaiement', 'carte')" 
                                        class="group relative px-3 py-3 rounded-xl font-semibold text-xs transition-all transform hover:scale-105 {{ $modePaiement === 'carte' ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg' : 'bg-white/10 text-purple-200 hover:bg-white/20 border border-white/20' }}">
                                    <div class="flex flex-col items-center gap-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span>Carte</span>
                                    </div>
                                </button>
                            </div>
                        </div>

                        {{-- Encaisser : Montant reçu et monnaie (si espèce) --}}
@if($modePaiement === 'espece')
    <div class="mb-4 bg-white/5 rounded-2xl p-4 border border-white/10">
        <label class="block text-sm font-semibold mb-2 text-purple-300 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Somme encaissée
        </label>
        
        <div class="relative">
            <input 
                type="number" 
                step="1" 
                min="0" 
                wire:model.live="sommeEncaissee"
                placeholder="Entrez le montant reçu..."
                class="w-full px-4 py-3 rounded-xl bg-white/10 text-white text-lg font-semibold placeholder-purple-300/50 border border-white/20 focus:outline-none focus:border-purple-400 focus:bg-white/15 transition-all"
            >
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-purple-300 font-medium text-sm">FCFA</span>
        </div>

        {{-- Boutons de montants rapides --}}
        <div class="mt-3 grid grid-cols-4 gap-2">
            <button 
                type="button" 
                wire:click="ajouterMontantEncaisse(500)" 
                class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition-all transform hover:scale-105 active:scale-95 border border-white/20">
                +500
            </button>
            <button 
                type="button" 
                wire:click="ajouterMontantEncaisse(1000)" 
                class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition-all transform hover:scale-105 active:scale-95 border border-white/20">
                +1K
            </button>
            <button 
                type="button" 
                wire:click="ajouterMontantEncaisse(2000)" 
                class="px-3 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition-all transform hover:scale-105 active:scale-95 border border-white/20">
                +2K
            </button>
            <button 
                type="button" 
                wire:click="definirMontantExact()" 
                class="px-3 py-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-bold transition-all transform hover:scale-105 active:scale-95 shadow-lg">
                Exact
            </button>
        </div>

        {{-- Affichage du calcul --}}
        <div class="mt-4 space-y-2">
            {{-- Total à payer --}}
            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-white/10">
                <span class="text-sm text-purple-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Total à payer
                </span>
                <span class="font-bold text-white text-lg">{{ number_format($this->totalPanier, 0, ',', ' ') }} <span class="text-sm text-purple-300">FCFA</span></span>
            </div>

            {{-- Somme reçue --}}
            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg border border-white/10">
                <span class="text-sm text-purple-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Somme reçue
                </span>
                <span class="font-bold text-blue-300 text-lg">{{ number_format(floatval($sommeEncaissee ?? 0), 0, ',', ' ') }} <span class="text-sm text-purple-300">FCFA</span></span>
            </div>

            {{-- Monnaie à rendre avec indication visuelle --}}
            <div class="flex items-center justify-between p-4 rounded-xl border-2 transition-all
                {{ $this->monnaie > 0 ? 'bg-gradient-to-r from-emerald-500/20 to-green-500/20 border-emerald-400/50' : 
                   ($sommeEncaissee > 0 && $sommeEncaissee < $this->totalPanier ? 'bg-gradient-to-r from-red-500/20 to-rose-500/20 border-red-400/50' : 'bg-white/5 border-white/10') }}">
                <span class="text-sm font-semibold flex items-center gap-2
                    {{ $this->monnaie > 0 ? 'text-emerald-300' : 
                       ($sommeEncaissee > 0 && $sommeEncaissee < $this->totalPanier ? 'text-red-300' : 'text-purple-200') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($this->monnaie > 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @elseif($sommeEncaissee > 0 && $sommeEncaissee < $this->totalPanier)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        @endif
                    </svg>
                    {{ $sommeEncaissee > 0 && $sommeEncaissee < $this->totalPanier ? 'Montant insuffisant' : 'Monnaie à rendre' }}
                </span>
                <span class="font-bold text-2xl
                    {{ $this->monnaie > 0 ? 'text-emerald-300 animate-pulse' : 
                       ($sommeEncaissee > 0 && $sommeEncaissee < $this->totalPanier ? 'text-red-300' : 'text-white') }}">
                    {{ number_format($this->monnaie, 0, ',', ' ') }} <span class="text-sm">FCFA</span>
                </span>
            </div>

            {{-- Message d'aide --}}
            @if($sommeEncaissee > 0 && $sommeEncaissee < $this->totalPanier)
                <div class="flex items-start gap-2 p-3 bg-red-500/10 border border-red-400/30 rounded-lg">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                        <p class="text-xs text-red-300">
                        Il manque encore <span class="font-bold">{{ number_format(floatval($this->totalPanier - floatval($sommeEncaissee ?? 0)), 0, ',', ' ') }} FCFA</span>
                    </p>
                </div>
            @elseif($this->monnaie > 0)
                <div class="flex items-start gap-2 p-3 bg-emerald-500/10 border border-emerald-400/30 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-emerald-300">
                        <span class="font-bold">Parfait !</span> Remettez {{ number_format($this->monnaie, 0, ',', ' ') }} FCFA au client
                    </p>
                </div>
            @endif
        </div>
    </div>
@endif

                        {{-- Total avec effet premium --}}
                        <div class="relative mb-6 overflow-hidden rounded-2xl">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 animate-gradient-x"></div>
                            <div class="relative bg-gradient-to-r from-purple-500/90 to-pink-500/90 backdrop-blur-sm p-6 rounded-2xl shadow-2xl border border-white/20">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-purple-100 text-sm font-medium mb-1">Total à payer</p>
                                        <span class="text-white font-semibold text-lg">FCFA</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-white font-bold text-4xl block animate-pulse">{{ number_format($this->totalPanier, 0, ',', ' ') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Boutons d'action modernes --}}
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="viderPanier" 
                                    wire:confirm="Êtes-vous sûr de vouloir vider le panier ?"
                                    class="group relative bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-4 py-4 rounded-xl font-bold transition-all transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-2xl overflow-hidden">
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                <div class="relative flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Annuler</span>
                                </div>
                            </button>
                            <button wire:click="validerVente" 
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    class="group relative bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-4 py-4 rounded-xl font-bold transition-all transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden">
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
                                <div class="relative flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="validerVente" class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Valider
                                    </span>
                                    <span wire:loading wire:target="validerVente" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Traitement...
                                    </span>
                                </div>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


{{-- Styles personnalisés --}}
<style>
    @keyframes blob {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        25% {
            transform: translate(20px, -50px) scale(1.1);
        }
        50% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        75% {
            transform: translate(50px, 50px) scale(1.05);
        }
    }
    
    .animate-blob {
        animation: blob 7s infinite;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    @keyframes gradient-x {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }
    
    .animate-gradient-x {
        background-size: 200% 200%;
        animation: gradient-x 3s ease infinite;
    }
    
    /* Scrollbar personnalisée */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #a855f7, #ec4899);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #9333ea, #db2777);
    }
    
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Animation de pulsation douce */
    @keyframes soft-pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }
</style>

    {{-- Footer moderne avec info utilisateur --}}
    <div class="fixed bottom-0 left-0 right-0 z-50 backdrop-blur-xl bg-gradient-to-r from-slate-900/95 via-purple-900/95 to-slate-900/95 border-t border-white/10 shadow-2xl">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                {{-- Info utilisateur --}}
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center shadow-lg ring-2 ring-white/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-purple-300 font-medium">Connecté en tant que</p>
                            <p class="font-bold text-white">{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                    
                    <div class="h-8 w-px bg-white/20"></div>
                    
                    <div class="flex items-center gap-2">
                        <div class="px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg shadow-lg">
                            <p class="text-xs font-bold text-white">{{ strtoupper(auth()->user()->role) }}</p>
                        </div>
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
                    
                    <div class="flex items-center gap-2 text-purple-300 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="current-time" class="font-medium"></span>
                    </div>
                </div>

                {{-- Actions rapides --}}
                <div class="flex items-center gap-2">
                    <button class="p-2 hover:bg-white/10 rounded-lg transition-all transform hover:scale-110 active:scale-95" title="Paramètres">
                        <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                    
                    <div class="h-6 w-px bg-white/20"></div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white rounded-lg transition-all transform hover:scale-105 active:scale-95 border border-red-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="text-sm font-semibold hidden sm:inline">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="{{ asset('js/ticket-printer.js') }}"></script>
@endpush
@script
<script>
// Gestion du mode offline
let isOfflineMode = !navigator.onLine;

// Vérifier le statut
function checkOfflineStatus() {
    isOfflineMode = !navigator.onLine;
    $wire.call('updateOfflineStatus', isOfflineMode);
}

// Écouter les changements
window.addEventListener('online', () => {
    isOfflineMode = false;
    $wire.call('updateOfflineStatus', false);
    console.log('🟢 Connexion rétablie');
});

window.addEventListener('offline', () => {
    isOfflineMode = true;
    $wire.call('updateOfflineStatus', true);
    console.log('🔴 Mode hors ligne');
});

// Vérifier au chargement
checkOfflineStatus();

// Écouter la sauvegarde offline
$wire.on('save-offline-vente', async (event) => {
    if (!window.offlineSync) {
        alert('Service offline non disponible');
        return;
    }

    try {
        const id = await window.offlineSync.enregistrerVentePending(event.venteData);
        console.log('✅ Vente sauvegardée localement:', id);
        
        // Vérifier le nombre de ventes en attente
        const ventes = await window.offlineSync.getVentesPending();
        $wire.set('ventesPendingCount', ventes.length);
        
    } catch (error) {
        console.error('❌ Erreur sauvegarde offline:', error);
        alert('Erreur lors de la sauvegarde locale');
    }
});

// Écouter la synchronisation réussie
$wire.on('ventes-synchronisees', () => {
    $wire.call('$refresh');
});
    // Écouter l'événement de vente validée pour impression
    $wire.on('vente-validee', (event) => {
        console.log('Vente validée, ID:', event.venteId);
        
        // Animation de succès (si vous avez la lib confetti)
        // confetti({
        //     particleCount: 100,
        //     spread: 70,
        //     origin: { y: 0.6 }
        // });
        
        try {
            // Imprimer automatiquement
            await window.ticketPrinter.print(event.venteId, 'browser');
            
            // Ou demander à l'utilisateur
            // const method = confirm('Utiliser une imprimante Bluetooth ?') ? 'bluetooth' : 'browser';
            // await window.ticketPrinter.print(event.venteId, method);
        } catch (error) {
            console.error('Erreur impression:', error);
            alert('Erreur lors de l\'impression du ticket');
        }
    });

    // Écouter l'événement de réimpression
    $wire.on('reimprimer-ticket', (event) => {
        console.log('Réimprimer ticket, ID:', event.venteId);
        try {
            await window.ticketPrinter.print(event.venteId);
        } catch (error) {
            console.error('Erreur réimpression:', error);
            alert('Erreur lors de la réimpression');
        }
    });

    // Son et animation de confirmation lors de l'ajout au panier
    $wire.on('produit-ajoute', () => {
        // Animation subtile
        const button = event.target.closest('button');
        if (button) {
            button.classList.add('scale-95');
            setTimeout(() => button.classList.remove('scale-95'), 100);
        }
        
        // Optionnel: jouer un son
        // new Audio('/sounds/beep.mp3').play();
    });
    
    // Animation au chargement de la page
    document.addEventListener('DOMContentLoaded', () => {
        const elements = document.querySelectorAll('[class*="animate"]');
        elements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.05}s`;
        });
    });

    // Horloge en temps réel
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
@endscript

<script>
// ==========================================
// SYSTÈME DE DÉFILEMENT DES CATÉGORIES
// ==========================================

// Fonction de défilement des catégories
function scrollCategories(direction) {
    const container = document.getElementById('categories-container');
    const scrollAmount = 300; // Distance de défilement en pixels
    
    if (!container) {
        console.error('Conteneur des catégories introuvable');
        return;
    }
    
    if (direction === 'left') {
        container.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    } else if (direction === 'right') {
        container.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }
}

// Fonction pour vérifier et afficher/masquer les boutons de défilement
function updateScrollButtons() {
    const container = document.getElementById('categories-container');
    const scrollLeft = document.getElementById('scroll-left');
    const scrollRight = document.getElementById('scroll-right');
    
    if (!container || !scrollLeft || !scrollRight) {
        console.warn('Éléments de scroll non trouvés');
        return;
    }
    
    // Vérifier si le conteneur a du contenu débordant
    const hasOverflow = container.scrollWidth > container.clientWidth;
    
    if (!hasOverflow) {
        // Pas de débordement, masquer les boutons
        scrollLeft.classList.add('hidden');
        scrollRight.classList.add('hidden');
        return;
    }
    
    // Il y a du débordement, gérer l'affichage des boutons
    const isAtStart = container.scrollLeft <= 10;
    const isAtEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 10;
    
    // Bouton gauche : visible si on n'est pas au début
    if (isAtStart) {
        scrollLeft.classList.add('hidden');
    } else {
        scrollLeft.classList.remove('hidden');
    }
    
    // Bouton droit : visible si on n'est pas à la fin
    if (isAtEnd) {
        scrollRight.classList.add('hidden');
    } else {
        scrollRight.classList.remove('hidden');
    }
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation du système de défilement des catégories');
    
    const container = document.getElementById('categories-container');
    
    if (container) {
        // Vérifier initialement
        setTimeout(updateScrollButtons, 100);
        
        // Écouter le défilement pour mettre à jour les boutons
        container.addEventListener('scroll', updateScrollButtons);
        
        // Écouter le redimensionnement de la fenêtre
        window.addEventListener('resize', () => {
            setTimeout(updateScrollButtons, 100);
        });
        
        // Observer les changements de contenu (pour Livewire)
        const observer = new MutationObserver(() => {
            setTimeout(updateScrollButtons, 100);
        });
        
        observer.observe(container, { 
            childList: true, 
            subtree: true,
            attributes: true
        });
        
        console.log('✅ Système de défilement initialisé');
    } else {
        console.warn('⚠️ Conteneur des catégories non trouvé au chargement');
    }
});

// Pour Livewire : réinitialiser après chaque mise à jour
if (window.Livewire) {
    document.addEventListener('livewire:navigated', function() {
        console.log('🔄 Livewire navigated - mise à jour des boutons');
        setTimeout(updateScrollButtons, 200);
    });
    
    // Hook Livewire v3
    if (typeof Livewire.hook === 'function') {
        Livewire.hook('morph.updated', () => {
            console.log('🔄 Livewire morph updated');
            setTimeout(updateScrollButtons, 200);
        });
    }
    
    // Alternative pour Livewire v2
    if (typeof Livewire.on === 'function') {
        Livewire.on('contentChanged', () => {
            console.log('🔄 Content changed');
            setTimeout(updateScrollButtons, 200);
        });
    }
}

// Ajouter une classe CSS pour masquer la scrollbar horizontale par défaut
const style = document.createElement('style');
style.textContent = `
    .categories-scroll {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE et Edge */
    }
    
    .categories-scroll::-webkit-scrollbar {
        display: none; /* Chrome, Safari et Opera */
    }
    
    /* Afficher la scrollbar au survol pour debug */
    .categories-scroll:hover::-webkit-scrollbar {
        display: block;
        height: 4px;
    }
    
    .categories-scroll:hover::-webkit-scrollbar-thumb {
        background: rgba(168, 85, 247, 0.5);
        border-radius: 4px;
    }
    
    /* Animation des boutons */
    #scroll-left, #scroll-right {
        transition: all 0.3s ease;
    }
    
    #scroll-left:not(.hidden), #scroll-right:not(.hidden) {
        animation: fadeInScale 0.3s ease;
    }
    
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
`;
document.head.appendChild(style);
</script>

</div>