{{-- resources/views/livewire/admin/categories/partials/category-delete-modal.blade.php --}}

@if($showDeleteModal)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
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
                        {{-- Aperçu de la carte à supprimer (Simplifié) --}}
                        <div class="max-w-xs w-full bg-white rounded-2xl shadow-md border-2 overflow-hidden mb-4
                                    @if($categorieColorToDelete == 'red') border-red-300
                                    @elseif($categorieColorToDelete == 'orange') border-orange-300
                                    @elseif($categorieColorToDelete == 'amber') border-amber-300
                                    @elseif($categorieColorToDelete == 'yellow') border-yellow-300
                                    @elseif($categorieColorToDelete == 'lime') border-lime-300
                                    @elseif($categorieColorToDelete == 'green') border-green-300
                                    @elseif($categorieColorToDelete == 'emerald') border-emerald-300
                                    @elseif($categorieColorToDelete == 'teal') border-teal-300
                                    @elseif($categorieColorToDelete == 'cyan') border-cyan-300
                                    @elseif($categorieColorToDelete == 'sky') border-sky-300
                                    @elseif($categorieColorToDelete == 'blue') border-blue-300
                                    @elseif($categorieColorToDelete == 'indigo') border-indigo-300
                                    @elseif($categorieColorToDelete == 'violet') border-violet-300
                                    @elseif($categorieColorToDelete == 'purple') border-purple-300
                                    @elseif($categorieColorToDelete == 'pink') border-pink-300
                                    @elseif($categorieColorToDelete == 'rose') border-rose-300
                                    @else border-gray-300
                                    @endif">
                            <div class="h-2
                                        @if($categorieColorToDelete == 'red') bg-gradient-to-r from-red-400 to-red-600
                                        @elseif($categorieColorToDelete == 'orange') bg-gradient-to-r from-orange-400 to-orange-600
                                        @elseif($categorieColorToDelete == 'amber') bg-gradient-to-r from-amber-400 to-amber-600
                                        @elseif($categorieColorToDelete == 'yellow') bg-gradient-to-r from-yellow-400 to-yellow-600
                                        @elseif($categorieColorToDelete == 'lime') bg-gradient-to-r from-lime-400 to-lime-600
                                        @elseif($categorieColorToDelete == 'green') bg-gradient-to-r from-green-400 to-green-600
                                        @elseif($categorieColorToDelete == 'emerald') bg-gradient-to-r from-emerald-400 to-emerald-600
                                        @elseif($categorieColorToDelete == 'teal') bg-gradient-to-r from-teal-400 to-teal-600
                                        @elseif($categorieColorToDelete == 'cyan') bg-gradient-to-r from-cyan-400 to-cyan-600
                                        @elseif($categorieColorToDelete == 'sky') bg-gradient-to-r from-sky-400 to-sky-600
                                        @elseif($categorieColorToDelete == 'blue') bg-gradient-to-r from-blue-400 to-blue-600
                                        @elseif($categorieColorToDelete == 'indigo') bg-gradient-to-r from-indigo-400 to-indigo-600
                                        @elseif($categorieColorToDelete == 'violet') bg-gradient-to-r from-violet-400 to-violet-600
                                        @elseif($categorieColorToDelete == 'purple') bg-gradient-to-r from-purple-400 to-purple-600
                                        @elseif($categorieColorToDelete == 'pink') bg-gradient-to-r from-pink-400 to-pink-600
                                        @elseif($categorieColorToDelete == 'rose') bg-gradient-to-r from-rose-400 to-rose-600
                                        @else bg-gradient-to-r from-gray-400 to-gray-600
                                        @endif">
                            </div>
                            <div class="p-6 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                                             @if($categorieColorToDelete == 'red') bg-red-100 text-red-600
                                             @elseif($categorieColorToDelete == 'orange') bg-orange-100 text-orange-600
                                             @elseif($categorieColorToDelete == 'amber') bg-amber-100 text-amber-600
                                             @elseif($categorieColorToDelete == 'yellow') bg-yellow-100 text-yellow-600
                                             @elseif($categorieColorToDelete == 'lime') bg-lime-100 text-lime-600
                                             @elseif($categorieColorToDelete == 'green') bg-green-100 text-green-600
                                             @elseif($categorieColorToDelete == 'emerald') bg-emerald-100 text-emerald-600
                                             @elseif($categorieColorToDelete == 'teal') bg-teal-100 text-teal-600
                                             @elseif($categorieColorToDelete == 'cyan') bg-cyan-100 text-cyan-600
                                             @elseif($categorieColorToDelete == 'sky') bg-sky-100 text-sky-600
                                             @elseif($categorieColorToDelete == 'blue') bg-blue-100 text-blue-600
                                             @elseif($categorieColorToDelete == 'indigo') bg-indigo-100 text-indigo-600
                                             @elseif($categorieColorToDelete == 'violet') bg-violet-100 text-violet-600
                                             @elseif($categorieColorToDelete == 'purple') bg-purple-100 text-purple-600
                                             @elseif($categorieColorToDelete == 'pink') bg-pink-100 text-pink-600
                                             @elseif($categorieColorToDelete == 'rose') bg-rose-100 text-rose-600
                                             @else bg-gray-100 text-gray-600
                                             @endif">
                                    <i class="fas fa-ice-cream text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $categorieNomToDelete }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <p class="text-gray-700 text-lg mb-3">
                        Vous êtes sur le point de supprimer la catégorie :
                    </p>
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 mb-4">
                        <p class="text-2xl font-bold text-red-600">
                            {{ $categorieNomToDelete }}
                        </p>
                    </div>

                    @if($categorieProduitCount > 0)
                        <!-- AVERTISSEMENT : Catégorie contient des produits -->
                        <div class="bg-red-100 border-2 border-red-300 rounded-xl p-6 mb-4">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-ban text-white text-2xl"></i>
                                    </div>
                                </div>
                                <div class="text-left flex-1">
                                    <p class="font-bold text-red-800 text-xl mb-2">
                                        <i class="fas fa-exclamation-circle mr-2"></i>Suppression impossible !
                                    </p>
                                    <p class="text-red-700 text-base mb-3">
                                        Cette catégorie contient <span class="font-bold text-2xl">{{ $categorieProduitCount }}</span> produit(s) actif(s).
                                    </p>
                                    <div class="bg-white/50 rounded-lg p-3 border border-red-200">
                                        <p class="text-sm text-red-600 font-medium">
                                            <i class="fas fa-lightbulb mr-2"></i>Pour supprimer cette catégorie, vous devez d'abord :
                                        </p>
                                        <ul class="text-sm text-red-700 mt-2 space-y-1 ml-6">
                                            <li>• Réassigner les produits à une autre catégorie</li>
                                            <li>• Ou supprimer les produits de cette catégorie</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton fermé uniquement si des produits sont liés -->
                        <div class="flex justify-center pt-6 border-t border-gray-200">
                            <button wire:click="$set('showDeleteModal', false)"
                                    class="px-10 py-4 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                <i class="fas fa-check mr-2"></i>Compris
                            </button>
                        </div>
                    @else
                        <!-- AUTORISATION : Aucun produit lié -->
                        <p class="text-gray-600 text-md mb-6">
                            Veuillez confirmer la suppression définitive.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                            <button wire:click.prevent="delete"
                                    class="px-8 py-4 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                <i class="fas fa-trash-alt mr-2"></i>Oui, Supprimer définitivement
                            </button>
                            <button type="button" wire:click="$set('showDeleteModal', false)"
                                    class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                                <i class="fas fa-times mr-2"></i>Annuler
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif