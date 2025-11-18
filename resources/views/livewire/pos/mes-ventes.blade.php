<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 pb-24">
    {{-- Messages Flash --}}
    @if($messageSucces)
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-8"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-init="setTimeout(() => { show = false; $wire.effacerMessages() }, 3000)"
             class="fixed top-6 right-6 z-50 bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-lg border border-emerald-300/30">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-full p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-8"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-init="setTimeout(() => { show = false; $wire.effacerMessages() }, 4000)"
             class="fixed top-6 right-6 z-50 bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-lg border border-red-300/30">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-full p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <span class="font-semibold">{{ $messageErreur }}</span>
            </div>
        </div>
    @endif

    {{-- Modal détails vente --}}
    @if($showDetails && $this->venteDetails)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-fadeIn" 
             x-data 
             @click.self="$wire.fermerDetails()">
            <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-hidden shadow-2xl transform transition-all animate-slideUp">
                {{-- Header avec dégradé --}}
                <div class="bg-gradient-to-r from-slate-50 to-blue-50 dark:from-slate-700 dark:to-slate-700 px-8 py-4 border-b border-gray-200 dark:border-slate-600">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Filtres de recherche</h3>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    {{-- Date début --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Date début
                        </label>
                        <input type="date" 
                               wire:model.live="dateDebut"
                               class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white border-2 border-gray-200 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                    </div>

                    {{-- Date fin --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Date fin
                        </label>
                        <input type="date" 
                               wire:model.live="dateFin"
                               class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white border-2 border-gray-200 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                    </div>

                    {{-- Mode de paiement --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Mode de paiement
                        </label>
                        <select wire:model.live="modePaiementFiltre"
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white border-2 border-gray-200 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                            <option value="tous">Tous les modes</option>
                            <option value="espece">💵 Espèce</option>
                            <option value="mobile">📱 Mobile Money</option>
                            <option value="carte">💳 Carte bancaire</option>
                        </select>
                    </div>

                    {{-- Recherche --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Numéro de ticket
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   wire:model.live.debounce.300ms="recherche"
                                   placeholder="Ex: TKT-20250117-0001"
                                   class="w-full px-4 py-3 pl-11 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 border-2 border-gray-200 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Boutons période rapide --}}
                <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-slate-600">
                    <button wire:click="periodeAujourdhui" 
                            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Aujourd'hui
                    </button>
                    <button wire:click="periodeSemaine" 
                            class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Cette semaine
                    </button>
                    <button wire:click="periodeMois" 
                            class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Ce mois
                    </button>
                    <button wire:click="resetFiltres" 
                            class="bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des ventes moderne --}}
    <div class="container mx-auto px-4">
        @if($this->ventes->count() > 0)
            <div class="space-y-4">
                @foreach($this->ventes as $vente)
                    <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-slate-700 overflow-hidden hover:scale-[1.02] cursor-pointer"
                         wire:click="afficherDetails({{ $vente->id }})">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                {{-- Infos vente --}}
                                <div class="flex-1 space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md">
                                            {{ $vente->numero_ticket }}
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="font-medium">{{ $vente->date_vente->format('d/m/Y à H:i') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 px-3 py-1 bg-gray-100 dark:bg-slate-700 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $vente->mode_paiement_libelle }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm">
                                        <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            <span class="font-semibold">{{ $vente->details->sum('quantite') }} article(s)</span>
                                        </div>
                                        <div class="flex items-center gap-2 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 px-3 py-1.5 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            <span class="font-semibold">{{ $vente->details->count() }} produit(s)</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Montant et action --}}
                                <div class="flex items-center gap-4 lg:border-l lg:border-gray-200 dark:lg:border-slate-700 lg:pl-6">
                                    <div class="text-right flex-1">
                                        <p class="text-gray-500 dark:text-gray-400 text-xs font-medium mb-1">MONTANT TOTAL</p>
                                        <p class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 text-3xl font-bold">
                                            {{ number_format($vente->total, 0, ',', ' ') }} F
                                        </p>
                                    </div>
                                    <button wire:click.stop="reimprimerTicket({{ $vente->id }})" 
                                            class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white p-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-110 group-hover:rotate-12"
                                            title="Réimprimer le ticket">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Barre de progression subtile au survol --}}
                        <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination moderne --}}
            <div class="mt-8">
                {{ $this->ventes->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-16 text-center shadow-xl border border-gray-200 dark:border-slate-700">
                <div class="max-w-md mx-auto">
                    <div class="bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Aucune vente trouvée</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Il n'y a pas de ventes correspondant à vos critères de recherche.</p>
                    <button wire:click="resetFiltres" 
                            class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Réinitialiser les filtres
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Animations CSS personnalisées --}}
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slideUp {
        animation: slideUp 0.4s ease-out;
    }
</style>

@script
<script>
    // Écouter l'événement d'impression
    $wire.on('imprimer-ticket', (event) => {
        console.log('Imprimer ticket, ID:', event.venteId);
        // TODO: Implémenter l'impression
    });
</script>
@endscript-r from-blue-600 to-indigo-600 px-8 py-6 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="bg-white/20 backdrop-blur-lg rounded-xl px-4 py-2">
                                    <span class="text-white font-bold text-lg">{{ $this->venteDetails->numero_ticket }}</span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-white">Détails de la vente</h3>
                            <p class="text-blue-100 text-sm mt-1">{{ $this->venteDetails->date_vente->format('d/m/Y à H:i') }}</p>
                        </div>
                        <button wire:click="fermerDetails" 
                                class="bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white rounded-xl p-2 transition-all duration-200 hover:rotate-90">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Contenu avec scroll --}}
                <div class="overflow-y-auto max-h-[calc(90vh-180px)] p-8 space-y-6">
                    {{-- Infos générales en grille moderne --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-4 border border-blue-200 dark:border-blue-700">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="bg-blue-500 rounded-xl p-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Paiement</p>
                            </div>
                            <p class="text-gray-900 dark:text-white font-bold text-lg">{{ $this->venteDetails->mode_paiement_libelle }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl p-4 border border-purple-200 dark:border-purple-700">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="bg-purple-500 rounded-xl p-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Caissier</p>
                            </div>
                            <p class="text-gray-900 dark:text-white font-bold text-lg">{{ $this->venteDetails->caissier->name }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl p-4 border border-emerald-200 dark:border-emerald-700 col-span-2">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="bg-emerald-500 rounded-xl p-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Articles</p>
                            </div>
                            <p class="text-gray-900 dark:text-white font-bold text-2xl">{{ $this->venteDetails->details->sum('quantite') }} article(s)</p>
                        </div>
                    </div>

                    {{-- Liste des produits moderne --}}
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg p-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">Articles vendus</h4>
                        </div>
                        <div class="space-y-3">
                            @foreach($this->venteDetails->details as $detail)
                                <div class="bg-white dark:bg-slate-700/50 rounded-2xl p-4 border border-gray-200 dark:border-slate-600 hover:shadow-lg transition-all duration-200 hover:scale-[1.02]">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <h5 class="text-gray-900 dark:text-white font-bold text-lg mb-1">{{ $detail->produit->nom }}</h5>
                                            <div class="flex items-center gap-3 text-sm">
                                                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-lg font-medium">
                                                    Qté: {{ $detail->quantite }}
                                                </span>
                                                <span class="text-gray-600 dark:text-gray-400">
                                                    × {{ number_format($detail->prix_unitaire, 0, ',', ' ') }} FCFA
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-indigo-600 dark:text-indigo-400 font-bold text-xl">
                                                {{ number_format($detail->sous_total, 0, ',', ' ') }} F
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Note si existe --}}
                    @if($this->venteDetails->note)
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl p-4 border-l-4 border-amber-500">
                            <div class="flex items-start gap-3">
                                <div class="bg-amber-500 rounded-lg p-2 mt-1">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-amber-800 dark:text-amber-300 text-sm font-semibold mb-1">Note</p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $this->venteDetails->note }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Total avec effet de gradient --}}
                    <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 rounded-2xl p-6 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-blue-100 text-sm font-medium mb-1">MONTANT TOTAL</p>
                                <span class="text-white font-bold text-4xl tracking-tight">
                                    {{ number_format($this->venteDetails->total, 0, ',', ' ') }}
                                </span>
                                <span class="text-blue-100 text-xl ml-2">FCFA</span>
                            </div>
                            <div class="bg-white/20 backdrop-blur-lg rounded-2xl p-4">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions Footer --}}
                <div class="bg-gray-50 dark:bg-slate-700/30 px-8 py-5 flex gap-3 border-t border-gray-200 dark:border-slate-600">
                    <button wire:click="reimprimerTicket({{ $this->venteDetails->id }})" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Réimprimer
                    </button>
                    <button wire:click="fermerDetails" 
                            class="flex-1 bg-white dark:bg-slate-600 hover:bg-gray-100 dark:hover:bg-slate-500 text-gray-700 dark:text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 border border-gray-300 dark:border-slate-500 hover:shadow-lg">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Header moderne avec glassmorphism --}}
    <div class="bg-white/10 backdrop-blur-xl border-b border-white/20 sticky top-0 z-40 shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Historique des ventes</h1>
                        <p class="text-blue-200 text-sm">Consultez et gérez toutes vos transactions</p>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ route('caisse') }}" 
                       class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouvelle vente
                    </a>
                    <button wire:click="rafraichir" 
                            class="bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 border border-white/30 hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques modernes avec icônes et animations --}}
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="group bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-slate-700 hover:scale-105">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Nombre de ventes</p>
                <p class="text-gray-900 dark:text-white text-4xl font-bold">{{ $this->statistiquesPeriode['nombre_ventes'] }}</p>
                <div class="mt-3 flex items-center text-blue-600 dark:text-blue-400 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Transactions
                </div>
            </div>

            <div class="group bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-slate-700 hover:scale-105">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl p-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Chiffre d'affaires</p>
                <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ number_format($this->statistiquesPeriode['total_ca'], 0, ',', ' ') }} F</p>
                <div class="mt-3 flex items-center text-emerald-600 dark:text-emerald-400 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Revenu total
                </div>
            </div>

            <div class="group bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-slate-700 hover:scale-105">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl p-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Panier moyen</p>
                <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ number_format($this->statistiquesPeriode['panier_moyen'], 0, ',', ' ') }} F</p>
                <div class="mt-3 flex items-center text-purple-600 dark:text-purple-400 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Par transaction
                </div>
            </div>

            <div class="group bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-slate-700 hover:scale-105">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-3 shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Modes de paiement</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1 rounded-lg text-sm font-semibold">
                        💵 {{ $this->statistiquesPeriode['ventes_espece'] }}
                    </span>
                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-lg text-sm font-semibold">
                        📱 {{ $this->statistiquesPeriode['ventes_mobile'] }}
                    </span>
                    <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 px-3 py-1 rounded-lg text-sm font-semibold">
                        💳 {{ $this->statistiquesPeriode['ventes_carte'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres modernes COMPLETS --}}
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-blue-50 dark:from-slate-700 dark:to-slate-700 px-8 py-4 border-b border-gray-200 dark:border-slate-600">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Filtres de recherche</h3>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    {{-- Date début --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Date début
                        </label>
                        <input type="date" 
                               wire:model.live="dateDebut"
                               class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white border-2 border-gray-200 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                    </div>

                    {{-- Date fin --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Date fin
                        </label>
                        <input type="date" 
                               wire:model.live="dateFin"
                               class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white border-2 border-gray-200 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                    </div>

                    {{-- Mode de paiement --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Mode de paiement
                        </label>
                        <select wire:model.live="modePaiementFiltre"
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white border-2 border-gray-200 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                            <option value="tous">Tous les modes</option>
                            <option value="espece">💵 Espèce</option>
                            <option value="mobile">📱 Mobile Money</option>
                            <option value="carte">💳 Carte bancaire</option>
                        </select>
                    </div>

                    {{-- Recherche --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Numéro de ticket
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   wire:model.live.debounce.300ms="recherche"
                                   placeholder="Ex: TKT-20250117-0001"
                                   class="w-full px-4 py-3 pl-11 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 border-2 border-gray-200 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-400 focus:outline-none transition-all duration-200">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Boutons période rapide --}}
                <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-slate-600">
                    <button wire:click="periodeAujourdhui" 
                            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Aujourd'hui
                    </button>
                    <button wire:click="periodeSemaine" 
                            class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Cette semaine
                    </button>
                    <button wire:click="periodeMois" 
                            class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Ce mois
                    </button>
                    <button wire:click="resetFiltres" 
                            class="bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des ventes moderne --}}
    <div class="container mx-auto px-4">
        @if($this->ventes->count() > 0)
            <div class="space-y-4">
                @foreach($this->ventes as $vente)
                    <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-slate-700 overflow-hidden hover:scale-[1.02] cursor-pointer"
                         wire:click="afficherDetails({{ $vente->id }})">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                {{-- Infos vente --}}
                                <div class="flex-1 space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md">
                                            {{ $vente->numero_ticket }}
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="font-medium">{{ $vente->date_vente->format('d/m/Y à H:i') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 px-3 py-1 bg-gray-100 dark:bg-slate-700 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $vente->mode_paiement_libelle }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm">
                                        <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            <span class="font-semibold">{{ $vente->details->sum('quantite') }} article(s)</span>
                                        </div>
                                        <div class="flex items-center gap-2 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 px-3 py-1.5 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            <span class="font-semibold">{{ $vente->details->count() }} produit(s)</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Montant et action --}}
                                <div class="flex items-center gap-4 lg:border-l lg:border-gray-200 dark:lg:border-slate-700 lg:pl-6">
                                    <div class="text-right flex-1">
                                        <p class="text-gray-500 dark:text-gray-400 text-xs font-medium mb-1">MONTANT TOTAL</p>
                                        <p class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 text-3xl font-bold">
                                            {{ number_format($vente->total, 0, ',', ' ') }} F
                                        </p>
                                    </div>
                                    <button wire:click.stop="reimprimerTicket({{ $vente->id }})" 
                                            class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white p-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-110 group-hover:rotate-12"
                                            title="Réimprimer le ticket">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Barre de progression subtile au survol --}}
                        <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination moderne --}}
            <div class="mt-8">
                {{ $this->ventes->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-16 text-center shadow-xl border border-gray-200 dark:border-slate-700">
                <div class="max-w-md mx-auto">
                    <div class="bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Aucune vente trouvée</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Il n'y a pas de ventes correspondant à vos critères de recherche.</p>
                    <button wire:click="resetFiltres" 
                            class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Réinitialiser les filtres
                    </button>
                </div>
            </div>
        @endif
    </div>


{{-- Animations CSS personnalisées --}}
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slideUp {
        animation: slideUp 0.4s ease-out;
    }
</style>

@push('scripts')
<script src="{{ asset('js/ticket-printer.js') }}"></script>
@endpush

@script
<script>
    // Écouter l'événement d'impression
    $wire.on('imprimer-ticket', (event) => {
        console.log('Imprimer ticket, ID:', event.venteId);
        try {
            await window.ticketPrinter.print(event.venteId);
        } catch (error) {
            console.error('Erreur impression:', error);
            alert('Erreur lors de l\'impression');
        }
    });
</script>
@endscript
</div>