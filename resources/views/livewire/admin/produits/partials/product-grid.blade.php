{{-- resources/views/livewire/admin/produits/partials/product-grid.blade.php --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($produits as $produit)
        {{-- LA CLEF ABSOLUE : wire:key + $refresh sur le conteneur PARENT --}}
        <div 
            wire:key="produit-{{ $produit->id }}" 
            wire:click.capture=""
            class="cursor-pointer">
            
            @include('livewire.admin.produits.partials.product-card', ['produit' => $produit])
        </div>
    @endforeach
</div>

@if($produits->count() === 0)
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
    <i class="fas fa-ice-cream text-6xl text-gray-300 mb-4"></i>
    <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun produit trouvé</h3>
    <p class="text-gray-500">Commencez par ajouter votre premier produit</p>
</div>
@endif

<div class="mt-8">
    {{ $produits->links() }}
</div>