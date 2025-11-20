{{-- resources/views/livewire/admin/objectifs/partials/modal-suppression.blade.php --}}
@if($afficherModalSuppression)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 animate-scale-in">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                    <i class="fas fa-trash text-4xl text-red-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Confirmer la suppression</h3>
                <p class="text-gray-600 mb-8">
                    Êtes-vous sûr de vouloir supprimer cet objectif ? Cette action est irréversible.
                </p>
                
                <div class="flex gap-4">
                    <button wire:click="$set('afficherModalSuppression', false)"
                            class="flex-1 px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-xl transition-all">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </button>
                    <button wire:click="supprimerObjectif"
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-xl shadow-lg transition-all">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif