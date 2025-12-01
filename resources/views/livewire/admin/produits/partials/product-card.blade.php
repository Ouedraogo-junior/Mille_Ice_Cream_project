{{-- resources/views/livewire/admin/produits/partials/product-card.blade.php --}}
{{-- La variable $produit est passée à l'appelant --}}
@php
    // Vérifier si toutes les variantes GÉRÉES ont un stock épuisé (stock = 0)
    // Les variantes non gérées (stock illimité) ne comptent pas comme épuisées
    $variantesGerees = $produit->variants->filter(fn($v) => $v->gerer_stock ?? false);
    $stockEpuise = $variantesGerees->isNotEmpty() && $variantesGerees->every(fn($v) => $v->stock == 0);
@endphp

<div wire:key="product-{{ $produit->id }}" class="group bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl hover:border-cyan-300 transition-all duration-300 transform hover:-translate-y-1 {{ $stockEpuise ? 'opacity-50 grayscale' : '' }}">

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

        @if($produit->categorie)
            <div class="absolute top-3 left-3">
                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-semibold text-gray-700 rounded-full shadow-sm">
                    {{ $produit->categorie->nom }}
                </span>
            </div>
        @endif

        @if($stockEpuise)
            <div class="absolute top-3 right-3">
                <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                    <i class="fas fa-ban mr-1"></i>ÉPUISÉ
                </span>
            </div>
        @endif
    </div>

    <div class="p-5">
        <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">{{ $produit->nom }}</h3>

        <div class="space-y-2 mb-4">
            @foreach($produit->variants->take(2) as $v)
                @php
                    $gererStock = $v->gerer_stock ?? false;
                    $stockFaible = $gererStock && $v->stock <= ($v->seuil_alerte ?? 10);
                    $stockEpuiseVariant = $gererStock && $v->stock == 0;
                @endphp
                
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-lg p-3 border border-cyan-100 {{ $stockEpuiseVariant ? 'opacity-60' : '' }}">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold text-gray-700">{{ $v->nom }}</span>
                        <span class="text-lg font-bold text-cyan-600">{{ number_format($v->prix) }} F</span>
                    </div>
                    
                    <div class="flex items-center gap-2 text-xs">
                        @if($gererStock)
                            {{-- Stock géré : afficher le stock numérique --}}
                            <i class="fas fa-box text-gray-400"></i>
                            <span class="text-gray-600">Stock:</span>
                            <span class="{{ $stockEpuiseVariant ? 'text-red-700 font-bold' : ($stockFaible ? 'text-orange-500 font-bold' : 'text-emerald-600 font-semibold') }}">
                                {{ $v->stock }}
                            </span>
                            @if($stockEpuiseVariant)
                                <span class="ml-auto px-2 py-0.5 bg-red-600 text-white rounded-full text-xs font-bold">
                                    <i class="fas fa-ban mr-1"></i>Épuisé
                                </span>
                            @elseif($stockFaible)
                                <span class="ml-auto px-2 py-0.5 bg-orange-100 text-orange-600 rounded-full text-xs font-semibold animate-pulse">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Faible
                                </span>
                            @endif
                        @else
                            {{-- Stock non géré : afficher "illimité" --}}
                            <i class="fas fa-infinity text-green-500"></i>
                            <span class="text-green-600 font-semibold">Stock illimité</span>
                            <span class="ml-auto px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>Disponible
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

        <div class="flex gap-3 mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button wire:click="editer({{ $produit->id }})"
                    class="flex-1 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-edit"></i>
            </button>

            <button wire:click.prevent="confirmDelete({{ $produit->id }})"
                    class="flex-1 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</div>