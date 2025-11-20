// resources/js/offline-sync.js

class OfflineSync {
    constructor() {
        this.dbName = 'POSGlacierDB';
        this.dbVersion = 1;
        this.db = null;
        this.isOnline = navigator.onLine;
        this.syncQueue = [];
        
        this.init();
    }

    /**
     * Initialiser IndexedDB
     */
    async init() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onerror = () => reject(request.error);
            request.onsuccess = () => {
                this.db = request.result;
                console.log('[OfflineSync] Base de données initialisée');
                resolve();
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;

                // Store pour les produits
                if (!db.objectStoreNames.contains('produits')) {
                    const produitsStore = db.createObjectStore('produits', { keyPath: 'id' });
                    produitsStore.createIndex('categorie_id', 'categorie_id', { unique: false });
                    produitsStore.createIndex('nom', 'nom', { unique: false });
                }

                // Store pour les catégories
                if (!db.objectStoreNames.contains('categories')) {
                    db.createObjectStore('categories', { keyPath: 'id' });
                }

                // Store pour les ventes en attente
                if (!db.objectStoreNames.contains('ventes_pending')) {
                    const ventesStore = db.createObjectStore('ventes_pending', { 
                        keyPath: 'id', 
                        autoIncrement: true 
                    });
                    ventesStore.createIndex('timestamp', 'timestamp', { unique: false });
                    ventesStore.createIndex('synced', 'synced', { unique: false });
                }

                // Store pour la configuration
                if (!db.objectStoreNames.contains('config')) {
                    db.createObjectStore('config', { keyPath: 'key' });
                }

                console.log('[OfflineSync] Stores créés');
            };
        });
    }

    /**
     * Sauvegarder les produits en local
     */
    async sauvegarderProduits(produits) {
        const transaction = this.db.transaction(['produits'], 'readwrite');
        const store = transaction.objectStore('produits');

        // Vider d'abord
        await store.clear();

        // Ajouter tous les produits
        for (const produit of produits) {
            await store.add(produit);
        }

        console.log(`[OfflineSync] ${produits.length} produits sauvegardés`);
    }

    /**
     * Récupérer les produits locaux
     */
    async recupererProduits() {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['produits'], 'readonly');
            const store = transaction.objectStore('produits');
            const request = store.getAll();

            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Sauvegarder les catégories en local
     */
    async sauvegarderCategories(categories) {
        const transaction = this.db.transaction(['categories'], 'readwrite');
        const store = transaction.objectStore('categories');

        await store.clear();
        for (const categorie of categories) {
            await store.add(categorie);
        }

        console.log(`[OfflineSync] ${categories.length} catégories sauvegardées`);
    }

    /**
     * Récupérer les catégories locales
     */
    async recupererCategories() {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['categories'], 'readonly');
            const store = transaction.objectStore('categories');
            const request = store.getAll();

            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Enregistrer une vente en attente (offline)
     */
    async enregistrerVentePending(venteData) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['ventes_pending'], 'readwrite');
            const store = transaction.objectStore('ventes_pending');

            const vente = {
                ...venteData,
                timestamp: Date.now(),
                synced: false,
                tentatives: 0,
            };

            const request = store.add(vente);

            request.onsuccess = () => {
                console.log('[OfflineSync] Vente enregistrée localement:', request.result);
                resolve(request.result);
            };
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Récupérer toutes les ventes non synchronisées
     */
    async getVentesPending() {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['ventes_pending'], 'readonly');
            const store = transaction.objectStore('ventes_pending');
            const index = store.index('synced');
            const request = index.getAll(false);

            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Marquer une vente comme synchronisée
     */
    async marquerVenteSynchronisee(id) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['ventes_pending'], 'readwrite');
            const store = transaction.objectStore('ventes_pending');
            
            const getRequest = store.get(id);
            
            getRequest.onsuccess = () => {
                const vente = getRequest.result;
                if (vente) {
                    vente.synced = true;
                    vente.synced_at = Date.now();
                    
                    const updateRequest = store.put(vente);
                    updateRequest.onsuccess = () => resolve();
                    updateRequest.onerror = () => reject(updateRequest.error);
                }
            };
            getRequest.onerror = () => reject(getRequest.error);
        });
    }

    /**
     * Supprimer une vente synchronisée
     */
    async supprimerVenteSynchronisee(id) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['ventes_pending'], 'readwrite');
            const store = transaction.objectStore('ventes_pending');
            const request = store.delete(id);

            request.onsuccess = () => resolve();
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Synchroniser toutes les ventes en attente
     */
    async synchroniserVentes() {
        if (!this.isOnline) {
            console.log('[OfflineSync] Hors ligne, sync impossible');
            return { success: false, message: 'Hors ligne' };
        }

        const ventesPending = await this.getVentesPending();
        
        if (ventesPending.length === 0) {
            console.log('[OfflineSync] Aucune vente à synchroniser');
            return { success: true, count: 0 };
        }

        console.log(`[OfflineSync] ${ventesPending.length} vente(s) à synchroniser`);

        let synced = 0;
        let errors = 0;

        for (const vente of ventesPending) {
            try {
                // Envoyer au serveur
                const response = await fetch('/api/offline/sync-vente', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(vente),
                });

                if (response.ok) {
                    await this.marquerVenteSynchronisee(vente.id);
                    // Supprimer après 24h (optionnel)
                    // await this.supprimerVenteSynchronisee(vente.id);
                    synced++;
                    console.log(`[OfflineSync] Vente ${vente.id} synchronisée`);
                } else {
                    throw new Error(`HTTP ${response.status}`);
                }
            } catch (error) {
                console.error(`[OfflineSync] Erreur sync vente ${vente.id}:`, error);
                errors++;
            }
        }

        return {
            success: true,
            total: ventesPending.length,
            synced,
            errors,
        };
    }

    /**
     * Télécharger les données depuis le serveur
     */
    async telechargerDonnees() {
        if (!this.isOnline) {
            console.log('[OfflineSync] Hors ligne, téléchargement impossible');
            return false;
        }

        try {
            // Télécharger produits
            const produitsResponse = await fetch('/api/offline/produits', { credentials: 'same-origin' });
            const produits = await produitsResponse.json();
            await this.sauvegarderProduits(produits.data);

            // Télécharger catégories
            const categoriesResponse = await fetch('/api/offline/categories', { credentials: 'same-origin' });
            const categories = await categoriesResponse.json();
            await this.sauvegarderCategories(categories.data);

            // Sauvegarder la date de dernier téléchargement
            await this.setConfig('derniere_sync', Date.now());

            console.log('[OfflineSync] Données téléchargées avec succès');
            return true;
        } catch (error) {
            console.error('[OfflineSync] Erreur téléchargement:', error);
            return false;
        }
    }

    /**
     * Sauvegarder une config
     */
    async setConfig(key, value) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['config'], 'readwrite');
            const store = transaction.objectStore('config');
            const request = store.put({ key, value });

            request.onsuccess = () => resolve();
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Récupérer une config
     */
    async getConfig(key) {
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction(['config'], 'readonly');
            const store = transaction.objectStore('config');
            const request = store.get(key);

            request.onsuccess = () => resolve(request.result?.value);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Vérifier l'état en ligne/hors ligne
     */
    updateOnlineStatus() {
        this.isOnline = navigator.onLine;
        console.log(`[OfflineSync] État: ${this.isOnline ? 'EN LIGNE' : 'HORS LIGNE'}`);
        
        // Si on revient en ligne, synchroniser automatiquement
        if (this.isOnline) {
            this.synchroniserVentes();
        }
    }

    /**
     * Nettoyer les ventes synchronisées (plus de 7 jours)
     */
    async nettoyerVentesSynchronisees() {
        const transaction = this.db.transaction(['ventes_pending'], 'readwrite');
        const store = transaction.objectStore('ventes_pending');
        const index = store.index('synced');
        const request = index.getAll(true);

        request.onsuccess = () => {
            const ventes = request.result;
            const limite = Date.now() - (7 * 24 * 60 * 60 * 1000); // 7 jours

            ventes.forEach((vente) => {
                if (vente.synced_at && vente.synced_at < limite) {
                    store.delete(vente.id);
                }
            });
        };
    }
}

// Initialiser le service
const offlineSync = new OfflineSync();

// Écouter les changements de connexion
window.addEventListener('online', () => offlineSync.updateOnlineStatus());
window.addEventListener('offline', () => offlineSync.updateOnlineStatus());

// Exporter pour utilisation globale
window.offlineSync = offlineSync;

export default offlineSync;