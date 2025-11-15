<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glacier POS - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <livewire:styles />
</head>
<body class="h-full bg-slate-900 text-white">
    <div class="flex h-screen">
        <!-- Menu gauche -->
        <div class="w-64 bg-slate-800 p-6">
            <h1 class="text-2xl font-bold mb-10">GLACIER POS</h1>
            <nav class="space-y-4">
                <a href="/admin/produits" class="block py-3 px-4 rounded hover:bg-slate-700">Produits</a>
                <a href="/admin/caissiers" class="block py-3 px-4 rounded hover:bg-slate-700">Caissiers</a>
                <a href="/admin/rapports" class="block py-3 px-4 rounded hover:bg-slate-700">Rapports</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left py-3 px-4 rounded hover:bg-red-900">Déconnexion</button>
                </form>
            </nav>
        </div>

        <!-- Contenu -->
        <div class="flex-1 p-8 overflow-auto">
            @yield('content')
        </div>
    </div>
</body>
</html>