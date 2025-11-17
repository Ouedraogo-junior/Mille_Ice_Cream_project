<div class="min-h-screen">
    <!-- En-tête -->
    <div class="bg-white rounded-2xl shadow-sm border border-cyan-100 p-8 mb-8">
        <div class="flex items-center gap-3">
            <i class="fas fa-cog text-gray-500 text-3xl"></i>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Paramètres</h1>
                <p class="text-gray-500 mt-2">Personnalisez votre expérience Milla Ice Cream</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Menu latéral -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <nav class="space-y-2">
                    <button wire:click="$set('section', 'profil')" 
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left font-semibold transition-all
                                   {{ $section === 'profil' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </button>
                    
                    <button wire:click="$set('section', 'entreprise')" 
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left font-semibold transition-all
                                   {{ $section === 'entreprise' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-building"></i>
                        <span>Entreprise</span>
                    </button>
                    
                    <button wire:click="$set('section', 'notifications')" 
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left font-semibold transition-all
                                   {{ $section === 'notifications' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </button>
                    
                    <button wire:click="$set('section', 'securite')" 
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left font-semibold transition-all
                                   {{ $section === 'securite' ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>Sécurité</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- SECTION PROFIL -->
            @if($section === 'profil')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-user text-cyan-500"></i>
                        Informations du profil
                    </h2>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-1">Gestion du profil</p>
                                <p>Gérez vos informations personnelles (nom, email, mot de passe).</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i class="fas fa-user-edit"></i>
                        <span>Modifier mon profil</span>
                    </a>
                </div>
            @endif
            

            <!-- SECTION ENTREPRISE -->
            @if($section === 'entreprise')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-building text-cyan-500"></i>
                        Informations de l'entreprise
                    </h2>

                    <div class="space-y-6">
                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-image text-cyan-500 mr-2"></i>Logo de l'entreprise
                            </label>
                            <div class="flex items-center gap-6">
                                @if($logoPreview)
                                    <img src="{{ $logoPreview->temporaryUrl() }}" class="w-24 h-24 object-contain rounded-xl border-2 border-gray-200">
                                @elseif($logoActuel)
                                    <img src="{{ asset('storage/' . $logoActuel) }}" class="w-24 h-24 object-contain rounded-xl border-2 border-gray-200">
                                @else
                                    <div class="w-24 h-24 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-ice-cream text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                <label class="cursor-pointer">
                                    <input type="file" wire:model="logoPreview" accept="image/*" class="hidden">
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition">
                                        <i class="fas fa-upload"></i>
                                        Changer le logo
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Nom de l'entreprise -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-store text-cyan-500 mr-2"></i>Nom de l'entreprise
                            </label>
                            <input type="text" 
                                   wire:model="nomEntreprise" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <!-- Adresse -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-cyan-500 mr-2"></i>Adresse
                            </label>
                            <input type="text" 
                                   wire:model="adresse" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-cyan-500 mr-2"></i>Téléphone
                            </label>
                            <input type="text" 
                                   wire:model="telephone" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        </div>

                        <button wire:click="sauvegarderEntreprise" 
                                class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                            <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </div>
            @endif

            <!-- SECTION NOTIFICATIONS -->
            @if($section === 'notifications')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-bell text-cyan-500"></i>
                        Préférences de notifications
                    </h2>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <h4 class="font-semibold text-gray-800">Stock faible</h4>
                                <p class="text-sm text-gray-600">Recevoir une alerte quand un produit atteint le seuil critique</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="notifStockFaible" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <h4 class="font-semibold text-gray-800">Nouvelles ventes</h4>
                                <p class="text-sm text-gray-600">Notification à chaque nouvelle vente effectuée</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="notifVentes" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <h4 class="font-semibold text-gray-800">Rapport quotidien</h4>
                                <p class="text-sm text-gray-600">Recevoir un résumé des ventes chaque jour à 20h</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="notifRapport" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-500"></div>
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            <!-- SECTION SÉCURITÉ -->
            @if($section === 'securite')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-cyan-500"></i>
                        Sécurité et confidentialité
                    </h2>

                    <div class="space-y-6">
                        <!-- Changer le mot de passe -->
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                            <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                <i class="fas fa-key text-blue-500"></i>
                                Mot de passe
                            </h4>
                            <p class="text-sm text-gray-600 mb-4">Modifiez votre mot de passe régulièrement pour plus de sécurité</p>
                            <a href="{{ route('profile.edit') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 text-blue-600 font-semibold rounded-lg border border-blue-200 transition">
                                <i class="fas fa-lock"></i>
                                Changer mon mot de passe
                            </a>
                        </div>

                        <!-- Sessions actives -->
                        <div class="p-6 bg-gray-50 rounded-xl">
                            <h4 class="font-semibold text-gray-800 mb-4">Sessions actives</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-desktop text-gray-400 text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-gray-800">Session actuelle</p>
                                            <p class="text-sm text-gray-500">{{ request()->ip() }} • {{ now()->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>