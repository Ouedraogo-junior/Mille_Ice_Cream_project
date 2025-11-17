<form wire:submit.prevent="updatePassword" class="space-y-6">
    <!-- Mot de passe actuel -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-key text-cyan-500 mr-2"></i> Mot de passe actuel
        </label>
        <input type="password" 
               wire:model="current_password" 
               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500"
               required 
               autocomplete="current-password"
               placeholder="Entrez votre mot de passe actuel">
        @error('current_password') 
            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
            </p> 
        @enderror
    </div>

    <!-- Nouveau mot de passe -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-lock text-cyan-500 mr-2"></i> Nouveau mot de passe
        </label>
        <input type="password" 
               wire:model="password" 
               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500"
               required 
               autocomplete="new-password"
               placeholder="Entrez un nouveau mot de passe">
        @error('password') 
            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
            </p> 
        @enderror
        <p class="text-xs text-gray-500 mt-2">
            <i class="fas fa-info-circle mr-1"></i>
            Le mot de passe doit contenir au moins 8 caractères
        </p>
    </div>

    <!-- Confirmation du mot de passe -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-check-circle text-cyan-500 mr-2"></i> Confirmer le mot de passe
        </label>
        <input type="password" 
               wire:model="password_confirmation" 
               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500"
               required 
               autocomplete="new-password"
               placeholder="Confirmez votre nouveau mot de passe">
        @error('password_confirmation') 
            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
            </p> 
        @enderror
    </div>

    <!-- Bouton de sauvegarde -->
    <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
        <button type="submit"
                class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-shield-alt mr-2"></i> Mettre à jour le mot de passe
        </button>

        <div class="ml-auto">
            <x-action-message class="text-green-600 font-bold text-lg" on="password-updated">
                <i class="fas fa-check-circle mr-1"></i>
                Mot de passe mis à jour !
            </x-action-message>
        </div>
    </div>
</form>