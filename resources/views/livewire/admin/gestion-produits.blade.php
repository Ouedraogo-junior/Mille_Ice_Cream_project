<div class="min-h-screen">
    <!-- En-tête avec titre et bouton -->
    <div class="bg-white rounded-2xl shadow-sm border border-cyan-100 p-8 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-ice-cream text-cyan-500"></i>
                    Gestion des Produits
                </h1>
                <p class="text-gray-500 mt-2">Gérez votre catalogue de glaces et produits</p>
            </div>
            <button wire:click="ajouter" 
                    class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i>
                <span>Nouveau produit</span>
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-2xl shadow-sm border border-cyan-100 p-6 mb-8">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           wire:model.live="search" 
                           placeholder="Rechercher un produit..."
                           class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                </div>
            </div>
            <select wire:model.live="categorie_filter" 
                    class="px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent min-w-[200px]">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->nom }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Grille des produits -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($produits as $produit)
            <div wire:click="editer({{ $produit->id }})"
                 class="group cursor-pointer bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl hover:border-cyan-300 transition-all duration-300 transform hover:-translate-y-1">
                
                <!-- Image du produit -->
                <div class="relative h-48 bg-gradient-to-br from-cyan-50 to-blue-50 overflow-hidden">
                    @if($produit->image)
                        <img src="{{ asset('storage/' . $produit->image) }}" 
                             alt="{{ $produit->nom }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="flex items-center justify-center h-full">
                            <i class="fas fa-ice-cream text-6xl text-cyan-200"></i>
                        </div>
                    @endif
                    
                    <!-- Badge catégorie -->
                    @if($produit->categorie)
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-semibold text-gray-700 rounded-full shadow-sm">
                                {{ $produit->categorie->nom }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Contenu -->
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">{{ $produit->nom }}</h3>

                    <!-- Variantes -->
                    <div class="space-y-2 mb-4">
                        @foreach($produit->variants->take(2) as $v)
                            <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-lg p-3 border border-cyan-100">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-semibold text-gray-700">{{ $v->nom }}</span>
                                    <span class="text-lg font-bold text-cyan-600">{{ number_format($v->prix) }} F</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <i class="fas fa-box text-gray-400"></i>
                                    <span class="text-gray-600">Stock:</span>
                                    <span class="{{ $v->stock <= $v->seuil_alerte ? 'text-red-500 font-bold' : 'text-emerald-600 font-semibold' }}">
                                        {{ $v->stock }}
                                    </span>
                                    @if($v->stock <= $v->seuil_alerte)
                                        <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs font-semibold animate-pulse">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Faible
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if($produit->variants->count() > 2)
                            <div class="text-center text-xs text-gray-500 italic pt-1">
                                +{{ $produit->variants->count() - 2 }} autre(s) variante(s)
                            </div>
                        @endif
                    </div>

                    <!-- Bouton modifier -->
                    <button class="w-full py-2 px-4 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-sm">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message si aucun produit -->
    @if($produits->count() === 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-ice-cream text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun produit trouvé</h3>
            <p class="text-gray-500">Commencez par ajouter votre premier produit</p>
        </div>
    @endif

    <!-- Pagination -->
    <div class="mt-8">
        {{ $produits->links() }}
    </div>

    <!-- MODAL FORMULAIRE -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full my-8 max-h-[90vh] overflow-y-auto">
                <!-- En-tête modal -->
                <div class="sticky top-0 bg-gradient-to-r from-cyan-500 to-blue-500 text-white p-6 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-bold flex items-center gap-3">
                            <i class="fas fa-ice-cream"></i>
                            {{ $produit_id ? 'Modifier le produit' : 'Nouveau produit' }}
                        </h2>
                        <button wire:click="$set('showForm', false)" 
                                class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Image Upload -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-image text-cyan-500 mr-2"></i>Photo du produit
                        </label>
                        <div class="flex flex-col items-center">
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" 
                                     class="w-64 h-64 object-cover rounded-2xl shadow-lg border-4 border-cyan-100 mb-4">
                            @elseif($produit_id && $produits->contains('id', $produit_id))
                                @php $currentProduit = $produits->firstWhere('id', $produit_id); @endphp
                                @if($currentProduit?->image)
                                    <img src="{{ asset('storage/'.$currentProduit->image) }}" 
                                         class="w-64 h-64 object-cover rounded-2xl shadow-lg border-4 border-cyan-100 mb-4">
                                @else
                                    <div class="w-64 h-64 bg-gradient-to-br from-cyan-50 to-blue-50 border-4 border-dashed border-cyan-200 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="fas fa-image text-6xl text-cyan-300"></i>
                                    </div>
                                @endif
                            @else
                                <div class="w-64 h-64 bg-gradient-to-br from-cyan-50 to-blue-50 border-4 border-dashed border-cyan-200 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-image text-6xl text-cyan-300"></i>
                                </div>
                            @endif
                            
                            <label class="cursor-pointer">
                                <input type="file" wire:model="image" accept="image/*" class="hidden">
                                <span class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                                    <i class="fas fa-upload"></i>
                                    Choisir une photo
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Nom et Catégorie -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag text-cyan-500 mr-2"></i>Nom du produit
                            </label>
                            <input type="text" 
                                   wire:model="nom" 
                                   placeholder="Ex: Glace vanille, Bubble waffle..." 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-layer-group text-cyan-500 mr-2"></i>Catégorie
                            </label>
                            <select wire:model="categorie_id" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="">Choisir une catégorie</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Variantes -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-boxes text-cyan-500"></i>
                                Variantes (tailles, formats)
                            </h3>
                            <button wire:click="ajouterVariant" 
                                    class="flex items-center gap-2 px-4 py-2 bg-cyan-100 hover:bg-cyan-200 text-cyan-700 font-semibold rounded-lg transition">
                                <i class="fas fa-plus"></i>
                                Ajouter
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($variants as $index => $variant)
                                <div class="bg-gradient-to-r from-gray-50 to-cyan-50 rounded-xl p-5 border border-gray-200">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Nom</label>
                                            <input type="text" 
                                                   wire:model="variants.{{ $index }}.nom" 
                                                   placeholder="Petit, Grand, Pack 6..." 
                                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                        </div>
                                        <div class="col-span-6 md:col-span-3">
                                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Prix (FCFA)</label>
                                            <input type="number" 
                                                   wire:model="variants.{{ $index }}.prix" 
                                                   placeholder="Prix" 
                                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                        </div>
                                        <div class="col-span-6 md:col-span-3">
                                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Stock initial</label>
                                            <input type="number" 
                                                   wire:model="variants.{{ $index }}.stock" 
                                                   placeholder="Stock" 
                                                   value="999"
                                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                        </div>
                                        <div class="col-span-12 md:col-span-2 flex items-end">
                                            <button wire:click="supprimerVariant({{ $index }})" 
                                                    class="w-full px-3 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg font-semibold transition">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                        <button wire:click="sauvegarder" 
                                class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i>Sauvegarder
                        </button>
                        <button wire:click="$set('showForm', false)" 
                                class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>