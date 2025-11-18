<div class="min-h-screen bg-gradient-to-br from-slate-900 via-cyan-900 to-slate-900 pb-24 relative overflow-hidden">
    {{-- Animated background blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    {{-- Message de succès --}}
    @if($messageSucces)
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition
             x-init="setTimeout(() => { show = false; $wire.effacerMessage() }, 3000)"
             class="fixed top-6 right-6 z-50 bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-lg border border-emerald-300/30 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold">{{ $messageSucces }}</span>
        </div>
    @endif

    {{-- Header moderne --}}
    <div class="backdrop-blur-xl bg-white/10 border-b border-white/20 sticky top-0 z-40 shadow-2xl">
        <div class="container mx-auto px-6 py-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-cyan-400 to-blue-600 rounded-2xl p-3 shadow-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Tableau de bord</h1>
                        <p class="text-cyan-200 text-sm">Bonjour, {{ auth()->user()->name }} 👋</p>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button wire:click="rafraichir" 
                            class="bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white px-5 py-3 rounded-xl font-semibold transition-all duration-200 border border-white/30 hover:shadow-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span class="hidden sm:inline">Actualiser</span>
                    </button>
                    <a href="{{ route('caisse') }}" 
                       class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-6 py-3 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouvelle vente
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8 relative z-10">
        
        {{-- Sélecteur de période --}}
        <div class="flex gap-3 mb-8">
            <button wire:click="changerPeriode('today')" 
                    class="px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 {{ $periode === 'today' ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'bg-white/10 text-cyan-200 hover:bg-white/20 border border-white/20' }}">
                Aujourd'hui
            </button>
            <button wire:click="changerPeriode('week')" 
                    class="px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 {{ $periode === 'week' ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'bg-white/10 text-cyan-200 hover:bg-white/20 border border-white/20' }}">
                Cette semaine
            </button>
            <button wire:click="changerPeriode('month')" 
                    class="px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105 {{ $periode === 'month' ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-lg' : 'bg-white/10 text-cyan-200 hover:bg-white/20 border border-white/20' }}">
                Ce mois
            </button>
        </div>

        @php
            $stats = $periode === 'today' ? $this->statistiquesJour : 
                    ($periode === 'week' ? $this->statistiquesSemaine : $this->statistiquesMois);
        @endphp

        {{-- KPIs Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            {{-- Nombre de ventes --}}
            <div class="group bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 hover:border-cyan-400/50 transition-all duration-300 hover:shadow-2xl hover:shadow-cyan-500/20 transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl p-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-cyan-200 text-sm font-medium mb-2">Nombre de ventes</p>
                <p class="text-white text-4xl font-bold mb-2">{{ $stats['nombre_ventes'] }}</p>
                <div class="flex items-center text-cyan-300 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Transactions
                </div>
            </div>

            {{-- Chiffre d'affaires --}}
            <div class="group bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 hover:border-emerald-400/50 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/20 transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl p-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-cyan-200 text-sm font-medium mb-2">Chiffre d'affaires</p>
                <p class="text-white text-3xl font-bold mb-2">{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} F</p>
                <div class="flex items-center text-emerald-300 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Revenu total
                </div>
            </div>

            {{-- Panier moyen --}}
            <div class="group bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 hover:border-purple-400/50 transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/20 transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-cyan-200 text-sm font-medium mb-2">Panier moyen</p>
                <p class="text-white text-3xl font-bold mb-2">{{ number_format($stats['panier_moyen'], 0, ',', ' ') }} F</p>
                <div class="flex items-center text-purple-300 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Par transaction
                </div>
            </div>

            {{-- Modes de paiement --}}
            <div class="group bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 hover:border-amber-400/50 transition-all duration-300 hover:shadow-2xl hover:shadow-amber-500/20 transform hover:-translate-y-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-cyan-200 text-sm font-medium mb-3">Modes de paiement</p>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-cyan-200">💵 Espèces</span>
                        <span class="text-white font-semibold">{{ $stats['ventes_espece'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-cyan-200">📱 Mobile</span>
                        <span class="text-white font-semibold">{{ $stats['ventes_mobile'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-cyan-200">💳 Carte</span>
                        <span class="text-white font-semibold">{{ $stats['ventes_carte'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Objectif du jour (seulement pour aujourd'hui) --}}
        @if($periode === 'today')
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-white mb-1">Objectif du jour</h3>
                    <p class="text-cyan-200 text-sm">{{ number_format($this->objectifJour, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-white">{{ number_format($this->progressionObjectif, 1) }}%</p>
                    <p class="text-cyan-200 text-sm">Atteint</p>
                </div>
            </div>
            <div class="relative h-6 bg-white/10 rounded-full overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500 rounded-full transition-all duration-1000"
                     style="width: {{ min(100, $this->progressionObjectif) }}%"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-white text-xs font-bold drop-shadow-lg">
                        {{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} / {{ number_format($this->objectifJour, 0, ',', ' ') }} F
                    </span>
                </div>
            </div>
        </div>
        @endif

        {{-- Graphique ventes par heure (seulement aujourd'hui) --}}
        @if($periode === 'today')
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 mb-8">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Ventes par heure
            </h3>
            <div class="flex items-end justify-between gap-1 h-48">
                @foreach($this->ventesParHeure as $heure)
                    @php
                        $maxVentes = $this->ventesParHeure->max('nombre');
                        $hauteur = $maxVentes > 0 ? ($heure['nombre'] / $maxVentes) * 100 : 0;
                    @endphp
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="relative w-full">
                            <div class="bg-gradient-to-t from-cyan-500 to-blue-600 rounded-t-lg transition-all duration-500 hover:from-cyan-400 hover:to-blue-500 cursor-pointer"
                                 style="height: {{ $hauteur }}%"
                                 title="{{ $heure['heure'] }}: {{ $heure['nombre'] }} vente(s) - {{ number_format($heure['montant'], 0) }} F">
                            </div>
                            {{-- Tooltip au survol --}}
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl z-10">
                                <div class="font-bold">{{ $heure['nombre'] }} vente(s)</div>
                                <div>{{ number_format($heure['montant'], 0) }} F</div>
                            </div>
                        </div>
                        <span class="text-cyan-200 text-xs mt-2">{{ substr($heure['heure'], 0, -1) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Section avec 2 colonnes --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Top produits --}}
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Top 5 des produits
                </h3>
                <div class="space-y-4">
                    @forelse($this->topProduits as $index => $item)
                        <div class="group bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all border border-white/10">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-semibold truncate">{{ $item->produit->nom }}</h4>
                                    <p class="text-cyan-200 text-sm">{{ $item->total_quantite }} vendus</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-white font-bold text-lg">{{ number_format($item->total_ca, 0) }} F</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="bg-white/5 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <p class="text-cyan-200">Aucune vente pour le moment</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Dernières ventes --}}
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Dernières ventes
                    </h3>
                    <a href="{{ route('mes-ventes') }}" 
                       class="text-cyan-300 hover:text-cyan-200 text-sm font-semibold flex items-center gap-1">
                        Voir tout
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="space-y-3 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($this->dernieresVentes as $vente)
                        <div class="group bg-white/5 hover:bg-white/10 rounded-xl p-4 transition-all border border-white/10 cursor-pointer"
                             wire:click="$dispatch('voir-vente', { venteId: {{ $vente->id }} })">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="bg-cyan-500/20 text-cyan-300 px-3 py-1 rounded-lg text-xs font-bold">
                                        {{ $vente->numero_ticket }}
                                    </span>
                                    <span class="text-cyan-200 text-xs">
                                        {{ $vente->date_vente->format('H:i') }}
                                    </span>
                                </div>
                                <span class="text-white font-bold text-lg">
                                    {{ number_format($vente->total, 0) }} F
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-cyan-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                {{ $vente->details->sum('quantite') }} article(s)
                                <span class="mx-1">•</span>
                                {{ $vente->mode_paiement_libelle }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="bg-white/5 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-cyan-200">Aucune vente pour le moment</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('caisse') }}" 
               class="group bg-gradient-to-br from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 rounded-2xl p-6 transition-all shadow-xl hover:shadow-2xl transform hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 rounded-xl p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-bold text-lg mb-1">Nouvelle vente</h4>
                        <p class="text-emerald-100 text-sm">Commencer une transaction</p>
                    </div>
                    <svg class="w-6 h-6 text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('mes-ventes') }}" 
               class="group bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 rounded-2xl p-6 transition-all shadow-xl hover:shadow-2xl transform hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 rounded-xl p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-bold text-lg mb-1">Mes ventes</h4>
                        <p class="text-blue-100 text-sm">Historique complet</p>
                    </div>
                    <svg class="w-6 h-6 text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}" 
               class="group bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 rounded-2xl p-6 transition-all shadow-xl hover:shadow-2xl transform hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 rounded-xl p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-bold text-lg mb-1">Paramètres</h4>
                        <p class="text-purple-100 text-sm">Mon profil</p>
                    </div>
                    <svg class="w-6 h-6 text-white group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>


{{-- Styles CSS personnalisés --}}
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

    /* Scrollbar personnalisée */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #06b6d4, #3b82f6);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #0891b2, #2563eb);
    }
</style>

@script
<script>
    // Auto-refresh toutes les 5 minutes
    setInterval(() => {
        $wire.rafraichir();
    }, 300000);
    
    // Écouter l'événement voir-vente pour rediriger
    $wire.on('voir-vente', (event) => {
        window.location.href = '{{ route("mes-ventes") }}?vente=' + event.venteId;
    });
</script>
@endscript
</div>