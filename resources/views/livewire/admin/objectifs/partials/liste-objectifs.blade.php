@if($objectifs->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
        @foreach($objectifs as $obj)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                
                {{-- En-tête coloré selon statut --}}
                <div class="relative bg-gradient-to-r {{ $obj->statut === 'annule' ? 'from-gray-400 to-gray-500' : ($obj->estEnRetard() ? 'from-red-500 to-rose-600' : 'from-cyan-500 to-blue-600') }} p-6 text-white">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2 pr-8">{{ $obj->titre }}</h3>
                            <div class="flex flex-wrap gap-2 text-sm">
                                <span class="bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $obj->date_fin->format('d/m/Y') }}
                                </span>
                                @if($obj->estEnRetard() && $obj->statut === 'en_cours')
                                    <span class="bg-red-700 px-3 py-1 rounded-full">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        En retard
                                    </span>
                                @endif
                                @if($obj->statut === 'annule')
                                    <span class="bg-gray-700 px-3 py-1 rounded-full">
                                        <i class="fas fa-ban mr-1"></i>
                                        Annulé
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Menu actions --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 top-12 bg-white rounded-xl shadow-2xl py-2 w-48 z-50">
                                
                                @can('update', $obj)
                                <button wire:click="editerObjectif({{ $obj->id }})" 
                                        @click="open = false"
                                        class="w-full text-left px-4 py-3 hover:bg-cyan-50 text-gray-700 flex items-center gap-3 transition-colors">
                                    <i class="fas fa-edit text-cyan-600"></i>
                                    Modifier
                                </button>
                                @endcan

                                @if($obj->statut === 'en_cours')
                                    @can('update', $obj)
                                    <button wire:click="annulerObjectif({{ $obj->id }})" 
                                            @click="open = false"
                                            class="w-full text-left px-4 py-3 hover:bg-orange-50 text-gray-700 flex items-center gap-3 transition-colors">
                                        <i class="fas fa-ban text-orange-600"></i>
                                        Annuler
                                    </button>
                                    @endcan
                                @endif

                                @if($obj->statut === 'annule' && auth()->user()->role === 'admin')
                                    <button wire:click="reactiverObjectif({{ $obj->id }})" 
                                            @click="open = false"
                                            class="w-full text-left px-4 py-3 hover:bg-green-50 text-gray-700 flex items-center gap-3 transition-colors">
                                        <i class="fas fa-redo text-green-600"></i>
                                        Réactiver
                                    </button>
                                @endif

                                @can('delete', $obj)
                                <hr class="my-2">
                                <button wire:click="confirmerSuppression({{ $obj->id }})" 
                                        @click="open = false"
                                        class="w-full text-left px-4 py-3 hover:bg-red-50 text-red-600 flex items-center gap-3 transition-colors">
                                    <i class="fas fa-trash"></i>
                                    Supprimer
                                </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                    
                    {{-- Créateur --}}
                    @if($obj->createur)
                    <div class="text-xs text-white/80 flex items-center gap-2">
                        <i class="fas fa-user"></i>
                        Par {{ $obj->createur->name }}
                    </div>
                    @endif
                </div>

                {{-- Corps de la carte --}}
                <div class="p-6">
                    @if($obj->description)
                        <p class="text-gray-600 text-sm mb-6 line-clamp-2">{{ $obj->description }}</p>
                    @endif

                    {{-- Progression principale --}}
                    <div class="text-center mb-6">
                        @if($obj->unite === 'FCFA')
                            <div class="text-3xl md:text-4xl font-black text-emerald-600">
                                {{ number_format($obj->actuel, 0, ',', ' ') }} F
                            </div>
                            <div class="text-base text-gray-500 mt-1">
                                sur {{ number_format($obj->objectif, 0, ',', ' ') }} F
                            </div>
                        @else
                            <div class="text-3xl md:text-4xl font-black text-cyan-600">
                                {{ number_format($obj->actuel, 0, ',', ' ') }}
                                <span class="text-xl text-gray-500"> / {{ number_format($obj->objectif, 0, ',', ' ') }}</span>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">{{ $obj->unite }}</div>
                        @endif

                        {{-- Pourcentage --}}
                        <div class="inline-flex items-center gap-2 mt-4 px-6 py-2 rounded-full {{ $obj->progression() >= 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-cyan-100 text-cyan-700' }}">
                            <i class="fas {{ $obj->progression() >= 100 ? 'fa-check-circle' : 'fa-chart-line' }}"></i>
                            <span class="text-2xl font-black">{{ $obj->progression() }}%</span>
                        </div>
                    </div>

                    {{-- Barre de progression stylée --}}
                    <div class="relative w-full bg-gray-200 rounded-full h-4 overflow-hidden mb-6 shadow-inner">
                        <div class="h-full bg-gradient-to-r {{ $obj->progression() >= 100 ? 'from-emerald-400 to-green-500' : 'from-cyan-400 to-blue-500' }} transition-all duration-1000 ease-out relative"
                             style="width: {{ min($obj->progression(), 100) }}%">
                            <div class="absolute inset-0 bg-white/30 animate-pulse"></div>
                        </div>
                    </div>

                    {{-- Jours restants --}}
                    @if($obj->statut === 'en_cours')
                        @php
                            $joursRestants = now()->diffInDays($obj->date_fin, false);
                        @endphp
                        <div class="text-center mb-4">
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ $joursRestants < 0 ? 'bg-red-100 text-red-700' : ($joursRestants < 7 ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                <i class="fas fa-clock"></i>
                                @if($joursRestants < 0)
                                    En retard de {{ abs($joursRestants) }} jour(s)
                                @elseif($joursRestants == 0)
                                    Dernier jour !
                                @else
                                    {{ $joursRestants }} jour(s) restant(s)
                                @endif
                            </span>
                        </div>
                    @endif

                    {{-- Mise à jour rapide --}}
                    @if($obj->statut === 'en_cours')
                        @can('update', $obj)
                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-edit mr-1"></i>
                                    Mise à jour rapide
                                </label>
                                <input type="number" 
                                       step="0.01"
                                       value="{{ $obj->actuel }}"
                                       wire:change="mettreAJourProgression({{ $obj->id }}, $event.target.value)"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl text-center text-lg font-semibold focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none transition-all"
                                       placeholder="Nouvelle valeur">
                            </div>
                        @endcan
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    {{-- État vide --}}
    <div class="text-center py-20 bg-white rounded-3xl shadow-lg">
        <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-full mb-6">
            <i class="fas fa-bullseye text-6xl text-cyan-500"></i>
        </div>
        <h3 class="text-3xl font-bold text-gray-700 mb-3">Aucun objectif trouvé</h3>
        <p class="text-gray-500 mb-6">
            {{-- Suppression de la condition $recherche --}}
            Créez votre premier objectif pour motiver l'équipe !
        </p>
        @if(auth()->user()->role === 'admin')
        <button wire:click="ouvrirFormulaire"
                class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg inline-flex items-center gap-3 transform hover:scale-105 transition-all duration-300">
            <i class="fas fa-plus-circle"></i>
            Créer un objectif
        </button>
        @endif
    </div>
@endif