<div class="min-h-screen">
    <!-- En-tête -->
    <div class="bg-white rounded-2xl shadow-sm border border-cyan-100 p-8 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-layer-group text-purple-500"></i>
                    Gestion des Catégories
                </h1>
                <p class="text-gray-500 mt-2">Organisez vos produits par catégories</p>
            </div>
            <button wire:click="ajouter" 
                    class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i>
                <span>Nouvelle catégorie</span>
            </button>
        </div>
    </div>

    <!-- Grille des catégories -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @foreach($categories as $cat)
            <div wire:click="editer({{ $cat->id }})"
                 class="group cursor-pointer bg-white rounded-2xl shadow-sm border-2 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden
                        @if($cat->couleur)
                            border-{{ $cat->couleur }}-300 hover:border-{{ $cat->couleur }}-500
                        @else
                            border-gray-300 hover:border-cyan-500
                        @endif">
                
                <!-- Bande de couleur en haut -->
                <div class="h-2 
                            @if($cat->couleur)
                                bg-gradient-to-r from-{{ $cat->couleur }}-400 to-{{ $cat->couleur }}-600
                            @else
                                bg-gradient-to-r from-cyan-400 to-blue-600
                            @endif">
                </div>
                
                <!-- Contenu -->
                <div class="p-6 text-center">
                    <!-- Icône de catégorie -->
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                                @if($cat->couleur)
                                    bg-{{ $cat->couleur }}-100 text-{{ $cat->couleur }}-600
                                @else
                                    bg-cyan-100 text-cyan-600
                                @endif
                                group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-ice-cream text-2xl"></i>
                    </div>
                    
                    <!-- Nom de la catégorie -->
                    <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">{{ $cat->nom }}</h3>
                    
                    <!-- Nombre de produits -->
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-box"></i>
                        <span>{{ $cat->produits_count ?? 0 }} produit(s)</span>
                    </div>
                    
                    <!-- Badge couleur -->
                    @if($cat->couleur)
                        <div class="mt-3 flex justify-center">
                            <div class="w-8 h-8 rounded-full border-2 border-white shadow-md
                                        bg-{{ $cat->couleur }}-500">
                            </div>
                        </div>
                    @endif
                    
                    <!-- Texte hover -->
                    <div class="mt-3 text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-edit mr-1"></i>Cliquer pour modifier
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message si aucune catégorie -->
    @if($categories->count() === 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucune catégorie</h3>
            <p class="text-gray-500 mb-6">Créez votre première catégorie pour organiser vos produits</p>
            <button wire:click="ajouter" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                <i class="fas fa-plus"></i>
                <span>Créer une catégorie</span>
            </button>
        </div>
    @endif

    <!-- MODAL FORMULAIRE -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full my-8">
                <!-- En-tête modal -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-bold flex items-center gap-3">
                            <i class="fas fa-layer-group"></i>
                            {{ $categorie_id ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
                        </h2>
                        <button wire:click="$set('showForm', false)" 
                                class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-8 max-h-[80vh] overflow-y-auto">
                    <!-- Nom de la catégorie -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-tag text-purple-500 mr-2"></i>Nom de la catégorie
                        </label>
                        <input type="text" 
                               wire:model="nom" 
                               placeholder="Ex: Glaces artisanales, Sorbets, Toppings..." 
                               class="w-full px-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg text-center">
                        @error('nom')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sélecteur de couleur -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 text-center">
                            <i class="fas fa-palette text-purple-500 mr-2"></i>Couleur de la catégorie
                        </label>
                        <p class="text-center text-gray-500 text-sm mb-6">Choisissez une couleur pour identifier facilement cette catégorie</p>
                        
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                            @foreach($couleurs as $color)
                                <div wire:click="$set('couleur', '{{ $color }}')" 
                                     class="cursor-pointer group">
                                    <div class="relative">
                                        <!-- Cercle de couleur -->
                                        <div class="w-14 h-14 rounded-xl 
                                                    {{ $couleur == $color ? 'ring-4 scale-110' : 'hover:scale-105' }}
                                                    transition-all duration-200 
                                                    shadow-md hover:shadow-lg
                                                    flex items-center justify-center
                                                    @if($color == 'red') bg-red-500 {{ $couleur == $color ? 'ring-red-300' : '' }}
                                                    @elseif($color == 'orange') bg-orange-500 {{ $couleur == $color ? 'ring-orange-300' : '' }}
                                                    @elseif($color == 'amber') bg-amber-500 {{ $couleur == $color ? 'ring-amber-300' : '' }}
                                                    @elseif($color == 'yellow') bg-yellow-500 {{ $couleur == $color ? 'ring-yellow-300' : '' }}
                                                    @elseif($color == 'lime') bg-lime-500 {{ $couleur == $color ? 'ring-lime-300' : '' }}
                                                    @elseif($color == 'green') bg-green-500 {{ $couleur == $color ? 'ring-green-300' : '' }}
                                                    @elseif($color == 'emerald') bg-emerald-500 {{ $couleur == $color ? 'ring-emerald-300' : '' }}
                                                    @elseif($color == 'teal') bg-teal-500 {{ $couleur == $color ? 'ring-teal-300' : '' }}
                                                    @elseif($color == 'cyan') bg-cyan-500 {{ $couleur == $color ? 'ring-cyan-300' : '' }}
                                                    @elseif($color == 'sky') bg-sky-500 {{ $couleur == $color ? 'ring-sky-300' : '' }}
                                                    @elseif($color == 'blue') bg-blue-500 {{ $couleur == $color ? 'ring-blue-300' : '' }}
                                                    @elseif($color == 'indigo') bg-indigo-500 {{ $couleur == $color ? 'ring-indigo-300' : '' }}
                                                    @elseif($color == 'violet') bg-violet-500 {{ $couleur == $color ? 'ring-violet-300' : '' }}
                                                    @elseif($color == 'purple') bg-purple-500 {{ $couleur == $color ? 'ring-purple-300' : '' }}
                                                    @elseif($color == 'fuchsia') bg-fuchsia-500 {{ $couleur == $color ? 'ring-fuchsia-300' : '' }}
                                                    @elseif($color == 'pink') bg-pink-500 {{ $couleur == $color ? 'ring-pink-300' : '' }}
                                                    @elseif($color == 'rose') bg-rose-500 {{ $couleur == $color ? 'ring-rose-300' : '' }}
                                                    @else bg-gray-500 {{ $couleur == $color ? 'ring-gray-300' : '' }}
                                                    @endif">
                                            <!-- Icône de validation si sélectionné -->
                                            @if($couleur == $color)
                                                <i class="fas fa-check text-white text-xl"></i>
                                            @endif
                                        </div>
                                        
                                        <!-- Nom de la couleur en petit -->
                                        <p class="text-xs text-center text-gray-500 mt-1 capitalize">{{ $color }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @error('couleur')
                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prévisualisation -->
                    @if($nom && $couleur)
                        <div class="mb-8 p-6 bg-gradient-to-br from-gray-50 to-cyan-50 rounded-xl border-2 border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-3 text-center">
                                <i class="fas fa-eye mr-2"></i>Aperçu
                            </p>
                            <div class="max-w-xs mx-auto bg-white rounded-2xl shadow-md border-2 overflow-hidden
                                        @if($couleur == 'red') border-red-300
                                        @elseif($couleur == 'orange') border-orange-300
                                        @elseif($couleur == 'amber') border-amber-300
                                        @elseif($couleur == 'yellow') border-yellow-300
                                        @elseif($couleur == 'lime') border-lime-300
                                        @elseif($couleur == 'green') border-green-300
                                        @elseif($couleur == 'emerald') border-emerald-300
                                        @elseif($couleur == 'teal') border-teal-300
                                        @elseif($couleur == 'cyan') border-cyan-300
                                        @elseif($couleur == 'sky') border-sky-300
                                        @elseif($couleur == 'blue') border-blue-300
                                        @elseif($couleur == 'indigo') border-indigo-300
                                        @elseif($couleur == 'violet') border-violet-300
                                        @elseif($couleur == 'purple') border-purple-300
                                        @elseif($couleur == 'pink') border-pink-300
                                        @elseif($couleur == 'rose') border-rose-300
                                        @else border-gray-300
                                        @endif">
                                <div class="h-2 
                                            @if($couleur == 'red') bg-gradient-to-r from-red-400 to-red-600
                                            @elseif($couleur == 'orange') bg-gradient-to-r from-orange-400 to-orange-600
                                            @elseif($couleur == 'amber') bg-gradient-to-r from-amber-400 to-amber-600
                                            @elseif($couleur == 'yellow') bg-gradient-to-r from-yellow-400 to-yellow-600
                                            @elseif($couleur == 'lime') bg-gradient-to-r from-lime-400 to-lime-600
                                            @elseif($couleur == 'green') bg-gradient-to-r from-green-400 to-green-600
                                            @elseif($couleur == 'emerald') bg-gradient-to-r from-emerald-400 to-emerald-600
                                            @elseif($couleur == 'teal') bg-gradient-to-r from-teal-400 to-teal-600
                                            @elseif($couleur == 'cyan') bg-gradient-to-r from-cyan-400 to-cyan-600
                                            @elseif($couleur == 'sky') bg-gradient-to-r from-sky-400 to-sky-600
                                            @elseif($couleur == 'blue') bg-gradient-to-r from-blue-400 to-blue-600
                                            @elseif($couleur == 'indigo') bg-gradient-to-r from-indigo-400 to-indigo-600
                                            @elseif($couleur == 'violet') bg-gradient-to-r from-violet-400 to-violet-600
                                            @elseif($couleur == 'purple') bg-gradient-to-r from-purple-400 to-purple-600
                                            @elseif($couleur == 'pink') bg-gradient-to-r from-pink-400 to-pink-600
                                            @elseif($couleur == 'rose') bg-gradient-to-r from-rose-400 to-rose-600
                                            @else bg-gradient-to-r from-gray-400 to-gray-600
                                            @endif">
                                </div>
                                <div class="p-4 text-center">
                                    <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center
                                                @if($couleur == 'red') bg-red-100 text-red-600
                                                @elseif($couleur == 'orange') bg-orange-100 text-orange-600
                                                @elseif($couleur == 'amber') bg-amber-100 text-amber-600
                                                @elseif($couleur == 'yellow') bg-yellow-100 text-yellow-600
                                                @elseif($couleur == 'lime') bg-lime-100 text-lime-600
                                                @elseif($couleur == 'green') bg-green-100 text-green-600
                                                @elseif($couleur == 'emerald') bg-emerald-100 text-emerald-600
                                                @elseif($couleur == 'teal') bg-teal-100 text-teal-600
                                                @elseif($couleur == 'cyan') bg-cyan-100 text-cyan-600
                                                @elseif($couleur == 'sky') bg-sky-100 text-sky-600
                                                @elseif($couleur == 'blue') bg-blue-100 text-blue-600
                                                @elseif($couleur == 'indigo') bg-indigo-100 text-indigo-600
                                                @elseif($couleur == 'violet') bg-violet-100 text-violet-600
                                                @elseif($couleur == 'purple') bg-purple-100 text-purple-600
                                                @elseif($couleur == 'pink') bg-pink-100 text-pink-600
                                                @elseif($couleur == 'rose') bg-rose-100 text-rose-600
                                                @else bg-gray-100 text-gray-600
                                                @endif">
                                        <i class="fas fa-ice-cream text-xl"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-800">{{ $nom }}</h3>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                        <button wire:click="sauvegarder" 
                                class="px-8 py-4 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i>Sauvegarder
                        </button>
                        <button wire:click="$set('showForm', false)" 
                                class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                        
                        @if($categorie_id)
                            <button wire:click="supprimer({{ $categorie_id }})" 
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette catégorie ?"
                                    class="px-8 py-4 bg-red-100 hover:bg-red-200 text-red-600 font-bold rounded-xl transition">
                                <i class="fas fa-trash mr-2"></i>Supprimer
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>