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

                <!-- Avatar simple -->
                <div class="flex flex-col items-center mb-10">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-5xl font-bold shadow-2xl ring-4 ring-cyan-100">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <p class="mt-4 text-gray-600 font-medium">{{ auth()->user()->name }}</p>
                    {{-- @if(auth()->user()->pseudo)
                        <p class="text-gray-500 text-sm">{{ auth()->user()->pseudo }}</p>
                    @endif --}}
                </div>

                <form wire:submit.prevent="updateProfileInformation" class="space-y-6">
                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-cyan-500 mr-2"></i> Nom complet
                        </label>
                        <input type="text" 
                               wire:model.blur="name" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all text-gray-800 placeholder:text-gray-400"
                               required autofocus autocomplete="name"
                               placeholder="Votre nom complet">
                        @error('name') 
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-cyan-500 mr-2"></i> Adresse email
                            <span class="text-gray-400 font-normal text-xs ml-1">(optionnel si pseudo renseigné)</span>
                        </label>
                        <input type="email" 
                               wire:model.blur="email" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all text-gray-800 placeholder:text-gray-400"
                               autocomplete="email"
                               placeholder="email@exemple.com">
                        @error('email') 
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p> 
                        @enderror

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && auth()->user()->email && !auth()->user()->hasVerifiedEmail())
                            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <p class="text-amber-800 text-sm flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Votre adresse email n'est pas vérifiée.
                                    <button type="button" 
                                            wire:click="resendVerificationNotification" 
                                            class="font-bold underline hover:text-amber-900 ml-2">
                                        Renvoyer le lien
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="text-green-600 font-semibold mt-3 flex items-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        Un nouveau lien a été envoyé sur votre boîte mail !
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Pseudo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-at text-cyan-500 mr-2"></i> Pseudo
                            <span class="text-gray-400 font-normal text-xs ml-1">(optionnel si email renseigné)</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   wire:model.blur="pseudo" 
                                   class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all text-gray-800 placeholder:text-gray-400"
                                   placeholder="votre_pseudo"
                                   maxlength="30"
                                   pattern="[a-zA-Z0-9_]+"
                                   autocomplete="username">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">@</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i>
                            Lettres, chiffres et underscores uniquement (max 30 caractères)
                        </p>
                        @error('pseudo') 
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Note importante -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-lightbulb text-blue-500 text-xl mt-0.5"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Information importante</p>
                                <p>Au moins un email <strong>OU</strong> un pseudo doit être renseigné pour vous connecter. Vous pouvez utiliser l'un ou l'autre (ou les deux).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de sauvegarde -->
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>

                        <div class="ml-auto">
                            <x-action-message class="text-green-600 font-bold text-lg flex items-center gap-2" on="profile-updated">
                                <i class="fas fa-check-circle"></i> Profil mis à jour avec succès !
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

            <!-- Bouton retour (visible uniquement aux admins) -->
            @can('admin')
            <div class="flex justify-start">
                <a href="{{ route('admin.settings') }}"
                   class="px-8 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Retour aux paramètres
                </a>
            </div>
            @endcan
        </div>
    </div>
</div>