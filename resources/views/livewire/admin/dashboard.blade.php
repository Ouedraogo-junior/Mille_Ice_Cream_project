<div class="min-h-screen">
    <!-- En-tête avec message de bienvenue -->
    <div class="bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-600 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-white">
                <h1 class="text-4xl font-bold mb-2 flex items-center gap-3">
                    <i class="fas fa-chart-line"></i>
                    Tableau de bord
                </h1>
                <p class="text-cyan-100 text-lg">
                    Bienvenue, <span class="font-bold">{{ auth()->user()->name }}</span> ! 
                    Voici votre aperçu en temps réel.
                </p>
            </div>
            <div class="text-center">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4">
                    <i class="fas fa-calendar-day text-white text-2xl mb-2"></i>
                    <p class="text-white font-semibold">{{ now()->locale('fr')->isoFormat('dddd') }}</p>
                    <p class="text-cyan-100 text-sm">{{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Ventes du jour -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="bg-gradient-to-r from-emerald-500 to-green-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                    </div>
                    <span class="text-white/80 text-sm font-semibold">Aujourd'hui</span>
                </div>
                <h3 class="text-white text-sm font-semibold mb-2">Ventes du jour</h3>
                @if($totalVentesAujourdHui > 0)
                    <p class="text-white text-3xl font-bold">{{ number_format($totalVentesAujourdHui, 0, ',', ' ') }} F</p>
                @else
                    <p class="text-white text-3xl font-bold">0 F</p>
                @endif
            </div>
            <div class="p-4 bg-gray-50">
                @if($totalVentesAujourdHui > 0)
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <i class="fas fa-arrow-up text-emerald-500"></i>
                        Objectif quotidien en cours
                    </p>
                @else
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <i class="fas fa-clock text-amber-500"></i>
                        En attente de la première vente
                    </p>
                @endif
            </div>
        </div>

        <!-- Total produits -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-ice-cream text-white text-2xl"></i>
                    </div>
                    <span class="text-white/80 text-sm font-semibold">Catalogue</span>
                </div>
                <h3 class="text-white text-sm font-semibold mb-2">Produits actifs</h3>
                <p class="text-white text-3xl font-bold">{{ $totalProduits }}</p>
            </div>
            <div class="p-4 bg-gray-50">
                <p class="text-sm text-gray-600 flex items-center gap-2">
                    <i class="fas fa-check-circle text-cyan-500"></i>
                    Disponibles à la vente
                </p>
            </div>
        </div>

        <!-- Produits en alerte -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="bg-gradient-to-r from-red-500 to-rose-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                    </div>
                    <span class="text-white/80 text-sm font-semibold">Stock</span>
                </div>
                <h3 class="text-white text-sm font-semibold mb-2">Stock faible</h3>
                <p class="text-white text-3xl font-bold">{{ $produitsEnRupture }}</p>
            </div>
            <div class="p-4 bg-gray-50">
                <p class="text-sm text-gray-600 flex items-center gap-2">
                    @if($produitsEnRupture > 0)
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        Variantes à réapprovisionner
                    @else
                        <i class="fas fa-check text-emerald-500"></i>
                        Tous les stocks sont bons
                    @endif
                </p>
            </div>
        </div>

        <!-- Caissiers actifs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <span class="text-white/80 text-sm font-semibold">Équipe</span>
                </div>
                <h3 class="text-white text-sm font-semibold mb-2">Caissiers</h3>
                <p class="text-white text-3xl font-bold">{{ $caissiersActifs }}</p>
            </div>
            <div class="p-4 bg-gray-50">
                <p class="text-sm text-gray-600 flex items-center gap-2">
                    <i class="fas fa-user-check text-indigo-500"></i>
                    Comptes enregistrés
                </p>
            </div>
        </div>
    </div>

    <!-- Grille à 2 colonnes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Produits en alerte de stock -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-box text-red-500"></i>
                    Alertes de stock
                </h2>
                <a href="{{ route('admin.produits') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold flex items-center gap-1">
                    Voir tout <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            @if($produitsEnAlerte->count() > 0)
                <div class="space-y-3">
                    @foreach($produitsEnAlerte as $variant)
                        <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $variant->produit->nom ?? 'Produit' }}</h4>
                                <p class="text-sm text-gray-600">{{ $variant->nom }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-red-600">{{ $variant->stock }}</p>
                                <p class="text-xs text-gray-500">en stock</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-check-circle text-6xl text-emerald-300 mb-4"></i>
                    <p class="text-gray-500 font-semibold">Aucune alerte de stock</p>
                    <p class="text-sm text-gray-400 mt-2">Tous vos produits sont bien approvisionnés</p>
                </div>
            @endif
        </div>

        <!-- Statistiques rapides -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-pie text-indigo-500"></i>
                Statistiques rapides
            </h2>

            <div class="space-y-4">
                <!-- Total variantes -->
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-xl border border-cyan-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-cyan-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-layer-group text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Variantes de produits</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalVariants }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total catégories -->
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tags text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Catégories actives</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalCategories }}</p>
                        </div>
                    </div>
                </div>

                <!-- Valeur du stock -->
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-warehouse text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Valeur totale du stock</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($valeurStock, 0, ',', ' ') }} F</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-gradient-to-br from-gray-50 to-cyan-50 rounded-2xl shadow-sm border border-gray-200 p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center flex items-center justify-center gap-2">
            <i class="fas fa-bolt text-cyan-500"></i>
            Actions rapides
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.produits') }}" 
               class="flex flex-col items-center gap-3 p-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 group">
                <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-rose-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus text-white text-2xl"></i>
                </div>
                <span class="font-semibold text-gray-700 group-hover:text-cyan-600">Ajouter un produit</span>
            </a>

            <a href="{{ route('admin.categories') }}" 
               class="flex flex-col items-center gap-3 p-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 group">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-layer-group text-white text-2xl"></i>
                </div>
                <span class="font-semibold text-gray-700 group-hover:text-cyan-600">Gérer les catégories</span>
            </a>

            <a href="{{ route('admin.caissiers') }}" 
               class="flex flex-col items-center gap-3 p-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 group">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <span class="font-semibold text-gray-700 group-hover:text-cyan-600">Ajouter un caissier</span>
            </a>

            <a href="{{ route('admin.rapports') }}" 
               class="flex flex-col items-center gap-3 p-6 bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 group">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-chart-line text-white text-2xl"></i>
                </div>
                <span class="font-semibold text-gray-700 group-hover:text-cyan-600">Voir les rapports</span>
            </a>
        </div>
    </div>

    <!-- Message motivationnel -->
    <div class="mt-8 text-center bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <div class="max-w-2xl mx-auto">
            <i class="fas fa-ice-cream text-6xl text-cyan-500 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">
                Tout est prêt pour une excellente journée ! 🎉
            </h3>
            <p class="text-gray-600">
                Votre glacier <span class="font-bold text-cyan-600">Mila Ice Cream</span> est opérationnel. 
                Offrez à vos clients la meilleure expérience glacée !
            </p>
        </div>
    </div>
</div>