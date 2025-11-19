{{-- resources/views/livewire/admin/produits/partials/filters.blade.php --}}
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
            {{-- La variable $categories est passée implicitement par Livewire --}}
            @foreach($categories as $c)
                 @if($c->active)
                    <option value="{{ $c->id }}">{{ $c->nom }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>