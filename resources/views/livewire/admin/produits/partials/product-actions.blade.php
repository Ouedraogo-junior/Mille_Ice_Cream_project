<div class="flex gap-3 mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
    <button wire:click.stop="editer({{ $produit->id }})"
            class="flex-1 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
        <i class="fas fa-edit"></i>
    </button>

    <button wire:click.stop="confirmDelete({{ $produit->id }})"
            class="flex-1 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
        <i class="fas fa-trash-alt"></i>
    </button>
</div>