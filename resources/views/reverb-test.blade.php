<!DOCTYPE html>
<html>
<head>
    <title>Test Laravel Reverb</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <h1>Test Reverb Laravel 12</h1>
    <button onclick="sendTestEvent()">Envoyer un événement test</button>
    <div id="logs" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;">
        <p style="color: #666;">En attente d'événements...</p>
    </div>

    <script>
        // Attendre que Echo soit disponible
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.Echo === 'undefined') {
                console.error('Echo n\'est pas chargé. Vérifiez que Vite est en cours d\'exécution.');
                document.getElementById('logs').innerHTML = '<p style="color:red;">❌ Echo non chargé. Exécutez: npm run dev</p>';
                return;
            }

            window.Echo.channel('test-channel')
                .listen('.test-event', (e) => {
                    const div = document.createElement('p');
                    div.style.color = 'green';
                    div.style.padding = '5px';
                    div.style.borderLeft = '3px solid green';
                    div.style.marginLeft = '10px';
                    div.textContent = `${new Date().toLocaleTimeString()} → ${e.message}`;
                    document.getElementById('logs').prepend(div);
                    console.log('Événement reçu:', e);
                });

            // Écouter les erreurs de connexion
            window.Echo.connector.pusher.connection.bind('error', (err) => {
                console.error('Erreur Reverb:', err);
                const div = document.createElement('p');
                div.style.color = 'red';
                div.textContent = `Erreur: ${err.error?.data?.message || err.message}`;
                document.getElementById('logs').prepend(div);
            });

            window.Echo.connector.pusher.connection.bind('connected', () => {
                console.log('✅ Connecté à Reverb!');
                const div = document.createElement('p');
                div.style.color = 'blue';
                div.textContent = `${new Date().toLocaleTimeString()} → ✅ Connecté à Reverb!`;
                document.getElementById('logs').prepend(div);
            });

            console.log('🎧 Écoute sur le canal test-channel...');
        });
    </script>

    <script>
        function sendTestEvent() {
            fetch('/test-reverb')
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    const div = document.createElement('p');
                    div.style.color = 'orange';
                    div.textContent = `${new Date().toLocaleTimeString()} → Événement envoyé au serveur`;
                    document.getElementById('logs').prepend(div);
                })
                .catch(error => console.error('Erreur:', error));
        }
    </script>
</body>
</html>