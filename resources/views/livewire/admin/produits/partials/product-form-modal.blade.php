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
                            type="button"
                            class="flex items-center gap-2 px-4 py-2 bg-cyan-100 hover:bg-cyan-200 text-cyan-700 font-semibold rounded-lg transition">
                        <i class="fas fa-plus"></i>
                        Ajouter
                    </button>
                </div>

                {{-- Info explicative --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">💡 Gestion du stock simplifiée :</p>
                            <ul class="list-disc list-inside space-y-1 ml-2">
                                <li><strong>Laissez vide</strong> le stock → Stock illimité (glaces de machine)</li>
                                <li><strong>Remplissez</strong> le stock → Gestion avec alertes (produits emballés)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($variants as $index => $variant)
                        <div class="bg-gradient-to-r from-gray-50 to-cyan-50 rounded-xl p-5 border-2 border-gray-200">
                            {{-- En-tête de la variante --}}
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-layer-group text-cyan-500"></i>
                                    Variante {{ $index + 1 }}
                                </h4>
                                @if(count($variants) > 1)
                                    <button wire:click="supprimerVariant({{ $index }})"
                                            type="button"
                                            class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg font-semibold text-sm transition">
                                        <i class="fas fa-trash mr-1"></i>Supprimer
                                    </button>
                                @endif
                            </div>

                            {{-- Nom et Prix --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">
                                        <i class="fas fa-tag text-gray-400 mr-1"></i>Nom de la variante
                                    </label>
                                    <input type="text"
                                           wire:model="variants.{{ $index }}.nom"
                                           placeholder="Ex: Petit, Moyen, Grand..."
                                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                    @error("variants.{$index}.nom")
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">
                                        <i class="fas fa-coins text-gray-400 mr-1"></i>Prix (FCFA)
                                    </label>
                                    <input type="number"
                                           wire:model="variants.{{ $index }}.prix"
                                           placeholder="Ex: 1500"
                                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                    @error("variants.{$index}.prix")
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Stock et Seuil (optionnels) --}}
                            <div class="bg-white rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <i class="fas fa-warehouse text-gray-600"></i>
                                    <h5 class="font-semibold text-gray-800 text-sm">Gestion du stock (optionnel)</h5>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-semibold text-gray-600 mb-1 block">
                                            <i class="fas fa-box text-gray-400 mr-1"></i>Stock disponible
                                            <span class="text-gray-400 font-normal italic">(vide = illimité)</span>
                                        </label>
                                        <input type="number"
                                               wire:model="variants.{{ $index }}.stock"
                                               wire:model.live="variants.{{ $index }}.stock"
                                               placeholder="Laisser vide pour stock illimité"
                                               min="0"
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                        <p class="text-xs text-gray-500 mt-1">
                                            @if(empty($variant['stock']) || $variant['stock'] === '')
                                                <i class="fas fa-infinity text-green-600"></i> <span class="text-green-600 font-semibold">Stock illimité</span>
                                            @else
                                                <i class="fas fa-box-open text-blue-600"></i> <span class="text-blue-600 font-semibold">Stock géré : {{ $variant['stock'] }} unités</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-gray-600 mb-1 block">
                                        <label class="text-xs font-semibold {{ (empty($variant['stock']) || $variant['stock'] === '') ? 'text-gray-400' : 'text-gray-600' }} mb-1 block">
                                            <i class="fas fa-exclamation-triangle text-orange-500 mr-1"></i>Seuil d'alerte
                                            <span class="text-gray-400 font-normal italic">(si stock géré)</span>
                                        </label>
                                        <input type="number"
                                               wire:model="variants.{{ $index }}.seuil_alerte"
                                               placeholder="Ex: 10"
                                               min="0"
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                               @if(empty($variant['stock']) || $variant['stock'] === '') disabled @endif>
                                        <p class="text-xs text-orange-600 mt-1">Alerte quand stock ≤ ce seuil</p>
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 {{ (empty($variant['stock']) || $variant['stock'] === '') ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                               @if(empty($variant['stock']) || $variant['stock'] === '') disabled @endif>
                                        <p class="text-xs {{ (empty($variant['stock']) || $variant['stock'] === '') ? 'text-gray-400' : 'text-orange-600' }} mt-1">
                                            Alerte quand stock ≤ ce seuil
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(count($variants) === 0)
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                            <p>Aucune variante. Cliquez sur "Ajouter" pour commencer.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                <button wire:click="sauvegarder"
                        type="button"
                        class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>Sauvegarder
                </button>
                <button wire:click="$set('showForm', false)"
                        type="button"
                        class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                    <i class="fas fa-times mr-2"></i>Annuler
                </button>
            </div>
        </div>
    </div>
</div>