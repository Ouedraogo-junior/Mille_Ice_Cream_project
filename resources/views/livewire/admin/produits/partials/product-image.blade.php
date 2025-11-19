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
</div>