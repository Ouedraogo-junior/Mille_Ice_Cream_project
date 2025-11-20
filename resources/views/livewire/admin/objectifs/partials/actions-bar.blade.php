{{-- resources/views/livewire/admin/objectifs/partials/actions-bar.blade.php --}}
<div class="flex flex-col md:flex-row gap-4 mb-8">
    {{-- Recherche --}}
    <div class="flex-1">
        <div class="relative">
            <input type="text" 
                   wire:model.live.debounce.300ms="recherche"
                   placeholder="Rechercher un objectif..." 
                   class="w-full pl-12 pr-4 py-4 bg-white border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none transition-all">
            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
    </div>

    {{-- Filtre par statut --}}
    <select wire:model.live="filtreStatut" 
            class="px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none">
        <option value="tous">Tous les statuts</option>
        <option value="en_cours">En cours</option>
        <option value="atteint">Atteints</option>
        <option value="annule">Annulés</option>
    </select>

    {{-- Tri --}}
    <select wire:model.live="triPar" 
            class="px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none">
        <option value="date_fin">Date de fin</option>
        <option value="progression">Progression</option>
        <option value="recent">Plus récents</option>
    </select>

    {{-- Bouton Nouvel objectif --}}
    @if(auth()->user()->role === 'admin')
    <button wire:click="ouvrirFormulaire"
            class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg flex items-center gap-3 transform hover:scale-105 transition-all duration-300 whitespace-nowrap">
        <i class="fas fa-plus-circle text-xl"></i>
        <span class="hidden md:inline">Nouvel objectif</span>
        <span class="md:hidden">Nouveau</span>
    </button>
    @endif
</div>