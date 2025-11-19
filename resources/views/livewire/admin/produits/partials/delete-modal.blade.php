{{-- resources/views/livewire/admin/produits/partials/delete-modal.blade.php --}}
{{-- Inclure uniquement le contenu de la modale (à l'intérieur du @if($showDeleteModal)) --}}
<div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full my-8">
        <div class="sticky top-0 bg-gradient-to-r from-red-500 to-rose-600 text-white p-6 rounded-t-2xl z-10">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    Confirmer la suppression
                </h2>
                <button wire:click="$set('showDeleteModal', false)"
                        class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-8">
            <div class="mb-6">
                <div class="flex flex-col items-center">
                    {{-- Logique d'affichage de l'image (copiée de l'original) --}}
                    @if($produitImageToDelete)
                        <img src="{{ asset('storage/' . $produitImageToDelete) }}"
                             alt="{{ $produitNomToDelete }}"
                             class="w-48 h-48 object-cover rounded-2xl shadow-lg border-4 border-red-100 mb-4">
                    @else
                        <div class="w-48 h-48 bg-gradient-to-br from-red-50 to-rose-50 border-4 border-dashed border-red-200 rounded-2xl flex items-center justify-center mb-4">
                            <i class="fas fa-ice-cream text-6xl text-red-300"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center mb-8">
                <p class="text-gray-700 text-lg mb-3">
                    Vous êtes sur le point de supprimer le produit :
                </p>
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 mb-4">
                    <p class="text-2xl font-bold text-red-600">
                        {{ $produitNomToDelete }}
                    </p>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-amber-600 text-xl mt-1"></i>
                        <div class="text-left">
                            <p class="font-semibold text-amber-800 mb-1">Attention !</p>
                            <p class="text-sm text-amber-700">
                                Cette action est <span class="font-bold">irréversible</span>.
                                Le produit, ses variantes et son image seront définitivement supprimés.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                <button wire:click="$set('showDeleteModal', false)"
                        class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                    <i class="fas fa-times mr-2"></i>Annuler
                </button>
                <button wire:click="supprimer({{ $produitToDelete }})"
                        wire:loading.attr="disabled"
                        class="px-8 py-4 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="supprimer">
                        <i class="fas fa-trash-alt mr-2"></i>Oui, supprimer
                    </span>
                    <span wire:loading wire:target="supprimer">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Suppression...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>