{{-- resources/views/livewire/admin/produits/partials/product-form-modal.blade.php --}}
{{-- Inclure uniquement le contenu de la modale (à l'intérieur du @if($showForm)) --}}
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full my-8 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-cyan-500 to-blue-500 text-white p-6 rounded-t-2xl z-10">
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
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-image text-cyan-500 mr-2"></i>Photo du produit
                </label>
                <div class="flex flex-col items-center">
                    {{-- Logique d'affichage de l'image (copiée de l'original) --}}
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
                        {{-- La variable $categories est passée implicitement --}}
                        @foreach($categories as $c)
                            @if($c->active || $c->id == $categorie_id)
                                <option value="{{ $c->id }}">
                                    {{ $c->nom }} @if(!$c->active) (Désactivée) @endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

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
                    {{-- Logique de la boucle de variantes (copiée de l'original) --}}
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