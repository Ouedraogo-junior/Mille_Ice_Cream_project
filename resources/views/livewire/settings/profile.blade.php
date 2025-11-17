<div>
    <div class="min-h-screen py-8">
        <!-- En-tête -->
        <div class="bg-white rounded-2xl shadow-sm border border-cyan-100 p-8 mb-8">
            <div class="flex items-center gap-4">
                <i class="fas fa-user-edit text-cyan-500 text-4xl"></i>
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">Modifier mon profil</h1>
                    <p class="text-gray-500 mt-2">Mettez à jour vos informations personnelles et votre mot de passe</p>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- SECTION 1 : INFORMATIONS DU PROFIL -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-user text-cyan-500"></i>
                    Informations du profil
                </h2>

                <!-- Avatar simple (sans Jetstream) -->
                <div class="flex flex-col items-center mb-10">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-5xl font-bold shadow-2xl ring-4 ring-cyan-100">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <p class="mt-4 text-gray-600 font-medium">{{ auth()->user()->name }}</p>
                </div>

                <form wire:submit.prevent="updateProfileInformation" class="space-y-6">
                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-cyan-500 mr-2"></i> Nom complet
                        </label>
                        <input type="text" 
                               wire:model.blur="name" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500"
                               required autofocus autocomplete="name">
                        @error('name') 
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-cyan-500 mr-2"></i> Adresse email
                        </label>
                        <input type="email" 
                               wire:model.blur="email" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500"
                               required autocomplete="email">
                        @error('email') 
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p> 
                        @enderror

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <p class="text-amber-800 text-sm">
                                    Votre adresse email n'est pas vérifiée.
                                    <button type="button" wire:click="resendVerificationNotification" 
                                            class="font-bold underline hover:text-amber-900 ml-2">
                                        Renvoyer le lien
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="text-green-600 font-semibold mt-3 text-center">
                                        Un nouveau lien a été envoyé sur votre boîte mail !
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Bouton de sauvegarde -->
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>

                        <div class="ml-auto">
                            <x-action-message class="text-green-600 font-bold text-lg" on="profile-updated">
                                Profil mis à jour avec succès !
                            </x-action-message>
                        </div>
                    </div>
                </form>
            </div>

            <!-- SECTION 2 : MISE À JOUR DU MOT DE PASSE -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                    <i class="fas fa-lock text-cyan-500"></i>
                    Modifier le mot de passe
                </h2>
                <p class="text-gray-500 mb-6">Assurez-vous d'utiliser un mot de passe long et sécurisé</p>

                <livewire:settings.password />
            </div>

            <!-- Bouton retour -->
            <div class="flex justify-start">
                <a href="{{ route('admin.settings') }}"
                   class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Retour aux paramètres
                </a>
            </div>

            <!-- SECTION 3 : ZONE DANGEREUSE -->
           {{--  <div class="bg-white rounded-2xl shadow-sm border border-red-200 p-8">
                <h3 class="text-2xl font-bold text-red-600 mb-6 flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    Zone dangereuse
                </h3>
                <livewire:settings.delete-user-form />
            </div> --}}
        </div>
    </div>
</div>