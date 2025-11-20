@if($afficherFormulaire)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-scale-in">
            
            {{-- En-tête du modal --}}
            <div class="sticky top-0 bg-gradient-to-r from-cyan-500 to-blue-600 p-6 rounded-t-3xl">
                <h2 class="text-3xl font-bold text-white flex items-center gap-3">
                    <i class="fas {{ $modeEdition ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                    {{ $modeEdition ? 'Modifier l\'objectif' : 'Nouvel objectif' }}
                </h2>
            </div>

            {{-- Formulaire --}}
            <form wire:submit.prevent="ajouterObjectif" class="p-8">
                
                {{-- Titre --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-heading text-cyan-600 mr-1"></i>
                        Titre de l'objectif *
                    </label>
                    <input type="text" 
                           wire:model="titre" 
                           placeholder="Ex: Atteindre 2 500 000 F en décembre" 
                           class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl text-lg focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none transition-all"
                           required>
                    @error('titre') 
                        <span class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left text-cyan-600 mr-1"></i>
                        Description (facultatif)
                    </label>
                    <textarea wire:model="description" 
                              rows="3" 
                              placeholder="Ajoutez des détails sur cet objectif..." 
                              class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none transition-all resize-none"></textarea>
                    @error('description') 
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Objectif et Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-bullseye text-cyan-600 mr-1"></i>
                            Valeur objectif *
                        </label>
                        <input type="number" 
                               step="0.01" 
                               wire:model="objectif" 
                               placeholder="Ex: 2500000" 
                               class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl text-lg focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none transition-all"
                               required>
                        @error('objectif') 
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-cyan-600 mr-1"></i>
                            Type d'objectif *
                        </label>
                        <select wire:model="type"
                                class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl text-lg focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none transition-all">
                            <option value="FCFA">💰 FCFA (Chiffre d'affaires)</option>
                            <option value="cornets">🍦 Cornets vendus</option>
                            <option value="boules">🍨 Boules de glace</option>
                            <option value="clients">👥 Clients servis</option>
                            <option value="commandes">📦 Commandes</option>
                            {{-- Suppression des options inutiles (kg, litres, pots) --}}
                        </select>
                        @error('type')
                            <span class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Date de fin --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check text-cyan-600 mr-1"></i>
                        Date de fin *
                    </label>
                    <input type="date" 
                           wire:model="date_fin" 
                           class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-cyan-300 focus:border-cyan-500 outline-none transition-all"
                           required>
                    @error('date_fin') 
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Boutons d'action --}}
                <div class="flex gap-4 justify-end">
                    <button type="button" 
                            wire:click="resetFormulaire"
                            class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas {{ $modeEdition ? 'fa-save' : 'fa-plus-circle' }} mr-2"></i>
                        {{ $modeEdition ? 'Enregistrer' : 'Créer l\'objectif' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif