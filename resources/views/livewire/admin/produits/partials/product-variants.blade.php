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