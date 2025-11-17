<!-- resources/views/partials/admin/sidebar.blade.php -->
<aside class="w-80 bg-white shadow-xl flex flex-col border-r border-cyan-100">
    <!-- En-tête avec Logo -->
    <div class="p-6 bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-600">
        <div class="flex flex-col items-center">
            <!-- Logo de l'entreprise -->
            <div class="w-24 h-24 mb-4 rounded-2xl bg-white/95 backdrop-blur-sm flex items-center justify-center shadow-2xl ring-4 ring-white/40 transform hover:scale-105 transition-transform duration-300">
                @php
                    $logoPath = \App\Models\Setting::get('logo');
                @endphp
                
                @if($logoPath && file_exists(storage_path('app/public/' . $logoPath)))
                    <img src="{{ asset('storage/' . $logoPath) }}" 
                         alt="Logo entreprise" 
                         class="w-20 h-20 object-contain rounded-xl">
                @elseif(file_exists(public_path('images/logo.jpeg')))
                    <img src="{{ asset('images/logo.jpeg') }}" 
                         alt="Mille Ice Cream Logo" 
                         class="w-20 h-20 object-contain">
                @else
                    {{-- Logo par défaut avec icône --}}
                    <i class="fas fa-ice-cream text-5xl bg-gradient-to-br from-cyan-500 to-blue-600 bg-clip-text text-transparent"></i>
                @endif
            </div>
            
            <!-- Nom de l'entreprise -->
            <h1 class="text-2xl font-extrabold text-white tracking-wide drop-shadow-lg">
                {{ \App\Models\Setting::get('nom_entreprise', 'Mille Ice Cream') }}
            </h1>
            <div class="mt-3 px-4 py-1.5 bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                <p class="text-xs font-semibold text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i> 
                    <span>Espace Admin</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-5 space-y-1.5 overflow-y-auto bg-gradient-to-b from-gray-50 to-white">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="group flex items-center gap-3 py-3.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('admin.dashboard') || request()->is('admin') 
                     ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/30' 
                     : 'text-gray-700 hover:bg-cyan-50 hover:text-cyan-700' }}">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center
                        {{ request()->routeIs('admin.dashboard') || request()->is('admin') 
                           ? 'bg-white/20' 
                           : 'bg-cyan-100 text-cyan-600 group-hover:bg-cyan-200' }}">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="flex-1">Dashboard</span>
            @if(request()->routeIs('admin.dashboard') || request()->is('admin'))
                <i class="fas fa-chevron-right text-sm"></i>
            @endif
        </a>

        <!-- Produits -->
        <a href="{{ route('admin.produits') }}" 
           class="group flex items-center gap-3 py-3.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('admin.produits') 
                     ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/30' 
                     : 'text-gray-700 hover:bg-cyan-50 hover:text-cyan-700' }}">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center
                        {{ request()->routeIs('admin.produits') 
                           ? 'bg-white/20' 
                           : 'bg-pink-100 text-pink-600 group-hover:bg-pink-200' }}">
                <i class="fas fa-ice-cream"></i>
            </div>
            <span class="flex-1">Produits</span>
            @if(request()->routeIs('admin.produits'))
                <i class="fas fa-chevron-right text-sm"></i>
            @endif
        </a>

        <!-- Catégories -->
        <a href="{{ route('admin.categories') }}" 
           class="group flex items-center gap-3 py-3.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('admin.categories') 
                     ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/30' 
                     : 'text-gray-700 hover:bg-cyan-50 hover:text-cyan-700' }}">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center
                        {{ request()->routeIs('admin.categories') 
                           ? 'bg-white/20' 
                           : 'bg-purple-100 text-purple-600 group-hover:bg-purple-200' }}">
                <i class="fas fa-layer-group"></i>
            </div>
            <span class="flex-1">Catégories</span>
            @if(request()->routeIs('admin.categories'))
                <i class="fas fa-chevron-right text-sm"></i>
            @endif
        </a>

        <!-- Caissiers -->
        <a href="{{ route('admin.caissiers') }}" 
           class="group flex items-center gap-3 py-3.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('admin.caissiers') 
                     ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/30' 
                     : 'text-gray-700 hover:bg-cyan-50 hover:text-cyan-700' }}">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center
                        {{ request()->routeIs('admin.caissiers') 
                           ? 'bg-white/20' 
                           : 'bg-indigo-100 text-indigo-600 group-hover:bg-indigo-200' }}">
                <i class="fas fa-users"></i>
            </div>
            <span class="flex-1">Caissiers</span>
            @if(request()->routeIs('admin.caissiers'))
                <i class="fas fa-chevron-right text-sm"></i>
            @endif
        </a>

        <!-- Rapports -->
        <a href="{{ route('admin.rapports') }}" 
           class="group flex items-center gap-3 py-3.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('admin.rapports') 
                     ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/30' 
                     : 'text-gray-700 hover:bg-cyan-50 hover:text-cyan-700' }}">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center
                        {{ request()->routeIs('admin.rapports') 
                           ? 'bg-white/20' 
                           : 'bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200' }}">
                <i class="fas fa-file-chart-line"></i>
            </div>
            <span class="flex-1">Rapports</span>
            @if(request()->routeIs('admin.rapports'))
                <i class="fas fa-chevron-right text-sm"></i>
            @endif
        </a>

        <!-- Séparateur -->
        <div class="py-2">
            <div class="border-t border-gray-200"></div>
        </div>

        <!-- Paramètres -->
        <a href="{{ route('admin.settings') ?? '#' }}" 
           class="group flex items-center gap-3 py-3.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('admin.settings') 
                     ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/30' 
                     : 'text-gray-700 hover:bg-cyan-50 hover:text-cyan-700' }}">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center
                        {{ request()->routeIs('admin.settings') 
                           ? 'bg-white/20' 
                           : 'bg-gray-100 text-gray-600 group-hover:bg-cyan-200 group-hover:text-cyan-700' }}">
                <i class="fas fa-cog"></i>
            </div>
            <span class="flex-1">Paramètres</span>
            @if(request()->routeIs('admin.settings'))
                <i class="fas fa-chevron-right text-sm"></i>
            @endif
        </a>
    </nav>

    <!-- Déconnexion -->
    <div class="p-5 border-t border-gray-200 bg-gray-50">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" 
                    class="group w-full flex items-center justify-center gap-3 py-3.5 px-5 rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </button>
        </form>
        
        <!-- Info utilisateur -->
        <div class="mt-3 text-center">
            <p class="text-xs text-gray-500">Connecté depuis</p>
            <p class="text-xs font-semibold text-gray-700 flex items-center justify-center gap-1.5 mt-1">
                <i class="fas fa-clock text-cyan-500"></i>
                {{ now()->format('H:i') }}
            </p>
        </div>
    </div>
</aside>