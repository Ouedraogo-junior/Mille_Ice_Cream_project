<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLACIER POS - CAISSE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
</head>
<body class="h-full bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 text-white min-h-screen">
    
    <!-- Contenu principal -->
    <div class="container mx-auto px-4 py-8">
        {{ $slot }}
    </div>

    <!-- Pied de page fixe avec info utilisateur -->
    <div class="fixed bottom-0 left-0 right-0 bg-black bg-opacity-80 p-4 text-center border-t-4 border-green-500">
        <p class="text-lg font-bold">
            Connecté : <span class="text-green-400">{{ auth()->user()->name }}</span> 
            | Rôle : <span class="text-yellow-400">{{ strtoupper(auth()->user()->role) }}</span>
        </p>
    </div>

    <livewire:scripts />
</body>
</html>