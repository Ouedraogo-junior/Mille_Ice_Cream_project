{{-- resources/views/livewire/admin/produits/partials/header.blade.php --}}
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