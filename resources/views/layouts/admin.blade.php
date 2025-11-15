<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} - Mille Ice Cream Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="h-full bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-50 text-gray-800 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        @include('partials.admin.sidebar')
        
        <!-- CONTENU PRINCIPAL -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header avec breadcrumb -->
            <header class="bg-white/80 backdrop-blur-md border-b border-cyan-100 px-8 py-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $header ?? 'Dashboard' }}</h2>
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                            <i class="fas fa-home text-cyan-500"></i>
                            <span>{{ $breadcrumb ?? 'Accueil' }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Connecté en tant que</p>
                            <p class="font-semibold text-cyan-700">{{ Auth::user()->name ?? 'Administrateur' }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-400 via-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-lg ring-4 ring-cyan-100">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenu avec scroll -->
            <main class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white/80 backdrop-blur-md border-t border-cyan-100 px-8 py-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-ice-cream text-cyan-500"></i>
                        &copy; {{ date('Y') }} Mille Ice Cream. Tous droits réservés.
                    </p>
                    <p class="text-gray-500">Version 1.0.0</p>
                </div>
            </footer>
        </div>
    </div>
    
    <livewire:scripts />
</body>
</html>