import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

console.log('🔧 Echo initialisé');

document.addEventListener('livewire:initialized', () => {
    console.log('✅ Livewire prêt');
    
    window.Echo.channel('admin-alerts')
        .listen('.stock.low', (e) => {
            console.log('📣 Alerte stock reçue:', e);
            
            // ✅ Filtrer les composants avec vérification de sécurité
            const components = Livewire.all();
            const notificationComponents = components.filter(c => {
                // Vérifier que __instance existe avant d'accéder à fingerprint
                return c.__instance && 
                       c.__instance.fingerprint && 
                       c.__instance.fingerprint.name === 'admin.notification-admin';
            });
            
            console.log(`🎯 ${notificationComponents.length} composant(s) NotificationAdmin trouvé(s)`);
            
            notificationComponents.forEach(component => {
                component.call('stockAlert', {
                    message: e.message,
                    restant: e.stockRestant,
                    seuil: e.seuil
                });
            });
            
            console.log('✅ Méthode stockAlert appelée sur tous les composants');
        });
    
    console.log('📡 Écoute active sur admin-alerts/.stock.low');
});