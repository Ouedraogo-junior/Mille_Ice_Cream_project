<div class="p-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-shield text-cyan-500 mr-2"></i>
                Gestion des Administrateurs
            </h2>
            <p class="text-gray-600 mt-1">Gérez les comptes administrateurs du système</p>
        </div>
        <button wire:click="ajouter" 
                class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
            <i class="fas fa-plus mr-2"></i> Nouvel Admin
        </button>
    </div>

    <!-- Formulaire d'ajout/modification -->
    @if($showForm)
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-l-4 border-cyan-500">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-{{ $user_id ? 'edit' : 'plus-circle' }} text-cyan-500 mr-2"></i>
            {{ $user_id ? 'Modifier' : 'Ajouter' }} un administrateur
        </h3>

        <form wire:submit.prevent="sauvegarder" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nom -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i> Nom complet *
                    </label>
                    <input type="text" wire:model="name" 
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                           placeholder="Ex: Jean Dupont">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1"></i> Email
                    </label>
                    <input type="email" wire:model="email" 
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                           placeholder="admin@exemple.com">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Pseudo -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-at mr-1"></i> Pseudo
                    </label>
                    <input type="text" wire:model="pseudo" 
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                           placeholder="Ex: admin123">
                    @error('pseudo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1"></i> Mot de passe {{ $user_id ? '(laisser vide pour ne pas changer)' : '*' }}
                    </label>
                    <input type="password" wire:model="password" 
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                           placeholder="••••••••">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Confirmation mot de passe -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1"></i> Confirmer le mot de passe
                    </label>
                    <input type="password" wire:model="password_confirmation" 
                           class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition"
                           placeholder="••••••••">
                </div>
            </div>

            <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg border border-blue-200">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                Vous devez renseigner au moins <strong>un email OU un pseudo</strong>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" wire:click="annuler" 
                        class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i> Annuler
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Liste des administrateurs -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Nom</th>
                    <th class="px-6 py-4 text-left font-semibold">Email</th>
                    <th class="px-6 py-4 text-left font-semibold">Pseudo</th>
                    <th class="px-6 py-4 text-left font-semibold">Créé le</th>
                    <th class="px-6 py-4 text-center font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($admins as $admin)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-cyan-400 to-blue-500 flex items-center justify-center text-white font-bold">
                                {{ $admin->initials() }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">{{ $admin->name }}</div>
                                @if($admin->id === auth()->id())
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Vous</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $admin->email ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $admin->pseudo ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $admin->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button wire:click="editer({{ $admin->id }})" 
                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="supprimer({{ $admin->id }})" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')"
                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-user-shield text-4xl mb-2 text-gray-300"></i>
                        <p>Aucun administrateur trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>