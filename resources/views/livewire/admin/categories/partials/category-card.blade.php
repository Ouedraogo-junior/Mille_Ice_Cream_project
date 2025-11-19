{{-- resources/views/livewire/admin/categories/partials/category-card.blade.php --}}
{{-- Variable $cat passée par l'itérateur --}}

{{-- 🔑 wire:key pour assurer la réconciliation du DOM 🔑 --}}
<div wire:key="category-{{ $cat->id }}" 
     class="group bg-white rounded-2xl shadow-sm border-2 overflow-hidden
            
            {{-- LOGIQUE: Griser si inactive --}}
            @if(!$cat->active) 
                opacity-60 grayscale hover:grayscale-0 
            @else
                hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2
            @endif

            {{-- Gestion des bordures (Couleur du bord pour l'état ACTIF/INACTIF) --}}
            @if($cat->couleur)
                border-{{ $cat->couleur }}-300 hover:border-{{ $cat->couleur }}-500
            @else
                border-gray-300 hover:border-cyan-500
            @endif">

    <div class="h-2
                @if($cat->couleur)
                    bg-gradient-to-r from-{{ $cat->couleur }}-400 to-{{ $cat->couleur }}-600
                @else
                    bg-gradient-to-r from-cyan-400 to-blue-600
                @endif">
    </div>

    <div class="p-6 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                    @if($cat->couleur)
                        bg-{{ $cat->couleur }}-100 text-{{ $cat->couleur }}-600
                    @else
                        bg-cyan-100 text-cyan-600
                    @endif
                    group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-ice-cream text-2xl"></i>
        </div>

        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">{{ $cat->nom }}</h3>

        {{-- État inactif affiché sous le nom --}}
        @if(!$cat->active)
             <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-600 mb-2">
                <i class="fas fa-eye-slash mr-1"></i>Désactivée
            </span>
        @endif

        <div class="flex items-center justify-center gap-2 text-sm text-gray-500 mb-3">
            <i class="fas fa-box"></i>
            <span>{{ $cat->produits_count }} produit(s)</span>
        </div>

        @if($cat->couleur)
            <div class="mb-3 flex justify-center">
                <div class="w-8 h-8 rounded-full border-2 border-white shadow-md
                             bg-{{ $cat->couleur }}-500">
                </div>
            </div>
        @endif

        <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            {{-- Bouton Modifier --}}
            <button wire:click.stop="editer({{ $cat->id }})"
                    class="flex-1 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-edit"></i>
            </button>

            {{-- Bouton Supprimer --}}
            <button wire:click.stop="confirmDelete({{ $cat->id }})"
                    class="flex-1 py-2 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-trash-alt"></i>
            </button>

            {{-- Bouton Activer / Désactiver --}}
            <button wire:click.stop="toggleActive({{ $cat->id }})"
                    class="flex-1 py-2 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105
                        {{ $cat->active 
                                ? 'bg-gradient-to-r from-gray-400 to-gray-600 hover:from-gray-500 hover:to-gray-700' 
                                : 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700' }}">
                @if($cat->active)
                    <i class="fas fa-eye-slash" title="Désactiver la catégorie"></i>
                @else
                    <i class="fas fa-eye" title="Activer la catégorie"></i>
                @endif
            </button>
        </div>
    </div>
</div>