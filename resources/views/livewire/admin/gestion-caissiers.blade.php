<div class="min-h-screen">
    <!-- En-tête -->
    <div class="bg-white rounded-2xl shadow-sm border border-cyan-100 p-8 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-users text-indigo-500"></i>
                    Gestion des Caissiers
                </h1>
                <p class="text-gray-500 mt-2">Gérez les comptes de vos employés</p>
            </div>
            <button wire:click="ajouter" 
                    class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <i class="fas fa-user-plus"></i>
                <span>Nouveau caissier</span>
            </button>
        </div>
    </div>

    <!-- Grille des caissiers -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($caissiers as $c)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl hover:border-indigo-300 transition-all duration-300 transform hover:-translate-y-1">
                <!-- Header avec dégradé -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6">
                    <!-- Avatar -->
                    <div class="flex justify-center mb-4">
                        <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-3xl font-bold text-indigo-600 shadow-lg ring-4 ring-white/30">
                            {{ strtoupper(substr($c->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <!-- Nom -->
                    <h3 class="text-xl font-bold text-white text-center">{{ $c->name }}</h3>
                </div>

                <!-- Contenu -->
                <div class="p-6">
                    <!-- Email -->
                    <div class="flex items-center gap-3 mb-4 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-envelope text-gray-400"></i>
                        <span class="text-sm text-gray-600 truncate">{{ $c->email }}</span>
                    </div>

                    <!-- Statut -->
                    <div class="flex items-center gap-3 mb-4 p-3 bg-emerald-50 rounded-lg">
                        <i class="fas fa-circle text-emerald-500 text-xs"></i>
                        <span class="text-sm text-emerald-700 font-semibold">Compte actif</span>
                    </div>

                    <!-- Date de création -->
                    <div class="flex items-center gap-3 mb-6 text-xs text-gray-500">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Créé le {{ $c->created_at->format('d/m/Y') }}</span>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex gap-3">
                        <a href="{{ route('mes-ventes', ['userId' => $c->id]) }}" target="_blank"
                           class="px-4 py-2.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-semibold rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-history"></i>
                        </a>
                        <button wire:click="editer({{ $c->id }})" 
                                class="flex-1 px-4 py-2.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>   
                        </button>
                        <button wire:click="supprimer({{ $c->id }})" 
                                wire:confirm="Êtes-vous sûr de vouloir supprimer ce caissier ?"
                                class="px-4 py-2.5 bg-red-100 hover:bg-red-200 text-red-600 font-semibold rounded-lg transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message si aucun caissier -->
    @if($caissiers->count() === 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun caissier</h3>
            <p class="text-gray-500 mb-6">Ajoutez votre premier employé caissier</p>
            <button wire:click="ajouter" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                <i class="fas fa-user-plus"></i>
                <span>Ajouter un caissier</span>
            </button>
        </div>
    @endif

    <!-- MODAL FORMULAIRE -->
    @if($showForm)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full my-8">
                <!-- En-tête modal -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white p-6 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-bold flex items-center gap-3">
                            <i class="fas fa-user-circle"></i>
                            {{ $user_id ? 'Modifier le caissier' : 'Nouveau caissier' }}
                        </h2>
                        <button wire:click="$set('showForm', false)" 
                                class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-8 max-h-[75vh] overflow-y-auto">
                    <!-- Avatar prévisualisation -->
                    @if($name)
                        <div class="flex justify-center mb-6">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-4xl font-bold text-white shadow-lg ring-4 ring-indigo-100">
                                {{ strtoupper(substr($name, 0, 1)) }}
                            </div>
                        </div>
                    @endif

                    <!-- Nom complet -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-indigo-500 mr-2"></i>Nom complet
                        </label>
                        <input type="text" 
                               wire:model="name" 
                               placeholder="Ex: Jean Dupont" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('name')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pseudo (optionnel si email rempli) -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-purple-500 mr-2"></i>
                            Pseudo de connexion <span class="text-gray-500 font-normal">(obligatoire si pas d’email)</span>
                        </label>
                        <input type="text"
                            wire:model="pseudo"
                            placeholder="ex: lucas2025"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('pseudo')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-indigo-500 mr-2"></i>Adresse email
                        </label>
                        <input type="email" 
                               wire:model="email" 
                               placeholder="caissier@milaicecream.com" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('email')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-indigo-500 mr-2"></i>Mot de passe
                        </label>
                        <input type="password" 
                               wire:model="password" 
                               placeholder="{{ $user_id ? 'Laisser vide pour conserver l\'ancien' : 'Mot de passe du compte' }}" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @if(!$user_id)
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Minimum 8 caractères
                            </p>
                        @endif
                        @error('password')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Note d'information -->
                    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-1">Informations importantes</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Le caissier recevra ses identifiants par email</li>
                                    <li>Il pourra se connecter à l'espace caissier</li>
                                    <li>Il aura accès uniquement aux ventes</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
                        <button wire:click="sauvegarder" 
                                class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i>Sauvegarder
                        </button>
                        <button wire:click="$set('showForm', false)" 
                                class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>