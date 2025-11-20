import '../../public/js/ticket-printer.js';
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

import './bootstrap';
import '../css/app.css';


// Importer le service offline
import offlineSync from './offline-sync';

// Enregistrer le Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(async registration => {
                console.log('✅ Service Worker enregistré:', registration.scope);

                // Attendre l'initialisation d'IndexedDB dans offlineSync
                try {
                    if (offlineSync && typeof offlineSync.init === 'function') {
                        await offlineSync.init();
                    }
                } catch (err) {
                    console.warn('⚠️ offlineSync.init() a échoué :', err);
                }

                // Télécharger les données initiales si en ligne
                if (navigator.onLine) {
                    offlineSync.telechargerDonnees();
                }
            })
            .catch(error => {
                console.error('❌ Erreur Service Worker:', error);
            });
    });
}

// Auto-sync toutes les 5 minutes si en ligne
setInterval(() => {
    if (navigator.onLine && window.offlineSync) {
        window.offlineSync.synchroniserVentes();
    }
}, 300000);