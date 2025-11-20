<!-- resources/views/components/offline-indicator.blade.php -->
<div x-data="offlineIndicator()" 
     x-init="init()"
     class="fixed bottom-6 left-6 z-50">
    
    <!-- Indicateur de statut -->
    <div class="flex items-center gap-3 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl px-6 py-4 border-2 transition-all duration-300"
         :class="{
             'border-green-500': isOnline,
             'border-red-500': !isOnline,
             'border-orange-500': isSyncing
         }">
        
        <!-- Statut LED -->
        <div class="relative">
            <div class="w-4 h-4 rounded-full transition-all"
                 :class="{
                     'bg-green-500': isOnline && !isSyncing,
                     'bg-red-500': !isOnline,
                     'bg-orange-500': isSyncing
                 }">
            </div>
            <div class="absolute inset-0 w-4 h-4 rounded-full animate-ping"
                 :class="{
                     'bg-green-400': isOnline && !isSyncing,
                     'bg-red-400': !isOnline,
                     'bg-orange-400': isSyncing
                 }"
                 x-show="!isOnline || isSyncing"></div>
        </div>

        <!-- Texte de statut -->
        <div>
            <p class="font-bold text-sm" :class="{
                'text-green-600 dark:text-green-400': isOnline && !isSyncing,
                'text-red-600 dark:text-red-400': !isOnline,
                'text-orange-600 dark:text-orange-400': isSyncing
            }">
                <span x-show="isOnline && !isSyncing">✓ En ligne</span>
                <span x-show="!isOnline">⚠ Mode hors ligne</span>
                <span x-show="isSyncing">🔄 Synchronisation...</span>
            </p>
            <p class="text-xs text-gray-600 dark:text-gray-400" x-show="pendingCount > 0">
                <span x-text="pendingCount"></span> vente(s) en attente
            </p>
        </div>

        <!-- Bouton de sync manuel -->
        <button @click="syncNow()" 
                x-show="isOnline && pendingCount > 0"
                class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                :disabled="isSyncing">
            Synchroniser
        </button>
    </div>

    <!-- Toast de notification -->
    <div x-show="showToast"
         x-transition
         class="mt-4 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-4 border-2 border-green-500">
        <p class="text-sm font-semibold text-green-600 dark:text-green-400" x-text="toastMessage"></p>
    </div>
</div>

<script>
function offlineIndicator() {
    return {
        isOnline: navigator.onLine,
        isSyncing: false,
        pendingCount: 0,
        showToast: false,
        toastMessage: '',

        init() {
            // Écouter les changements de connexion
            window.addEventListener('online', () => this.handleOnline());
            window.addEventListener('offline', () => this.handleOffline());

            // Vérifier les ventes en attente
            this.checkPendingVentes();
            
            // Vérifier périodiquement
            setInterval(() => this.checkPendingVentes(), 30000); // Toutes les 30s
        },

        async handleOnline() {
            this.isOnline = true;
            this.showNotification('Connexion rétablie ! Synchronisation en cours...');
            
            // Attendre un peu pour stabiliser la connexion
            setTimeout(() => this.syncNow(), 2000);
        },

        handleOffline() {
            this.isOnline = false;
            this.showNotification('Mode hors ligne activé. Les ventes seront synchronisées plus tard.');
        },

        async checkPendingVentes() {
            if (!window.offlineSync) return;
            
            try {
                const ventes = await window.offlineSync.getVentesPending();
                this.pendingCount = ventes.length;
            } catch (error) {
                console.error('Erreur check pending:', error);
            }
        },

        async syncNow() {
            if (!this.isOnline || this.isSyncing || !window.offlineSync) return;

            this.isSyncing = true;
            
            try {
                const result = await window.offlineSync.synchroniserVentes();
                
                if (result.success && result.synced > 0) {
                    this.showNotification(`${result.synced} vente(s) synchronisée(s) avec succès !`);
                    await this.checkPendingVentes();
                    
                    // Recharger les données
                    if (window.Livewire) {
                        window.Livewire.dispatch('ventes-synchronisees');
                    }
                }
            } catch (error) {
                console.error('Erreur sync:', error);
                this.showNotification('Erreur lors de la synchronisation');
            } finally {
                this.isSyncing = false;
            }
        },

        showNotification(message) {
            this.toastMessage = message;
            this.showToast = true;
            setTimeout(() => {
                this.showToast = false;
            }, 4000);
        }
    }
}
</script>