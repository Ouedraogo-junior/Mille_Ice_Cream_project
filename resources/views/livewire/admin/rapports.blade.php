<div class="min-h-screen">
    <!-- En-tête -->
    <div class="bg-white rounded-2xl shadow-sm border border-cyan-100 p-8 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-file-chart-line text-emerald-500"></i>
                    Rapports et Statistiques
                </h1>
                <p class="text-gray-500 mt-2">Analysez les performances de votre glacier</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="testerDonnees" 
            class="flex items-center gap-2 px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 font-semibold rounded-xl transition">
        <i class="fas fa-bug"></i>
        <span>Tester</span>
    </button>
                <button wire:click="exporterPDF" 
                        class="flex items-center gap-2 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-xl transition">
                    <i class="fas fa-file-pdf"></i>
                    <span>Exporter PDF</span>
                </button>
                <button wire:click="exporterExcel" 
                        class="flex items-center gap-2 px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 font-semibold rounded-xl transition">
                    <i class="fas fa-file-excel"></i>
                    <span>Exporter Excel</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Filtres de période -->
    <div class="bg-white rounded-2xl shadow-sm border border-cyan-100 p-6 mb-8">
        <div class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar text-cyan-500 mr-2"></i>Date de début
                </label>
                <input type="date" 
                       wire:model.live="dateDebut" 
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar text-cyan-500 mr-2"></i>Date de fin
                </label>
                <input type="date" 
                       wire:model.live="dateFin" 
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>
            <div class="flex gap-2">
                <button wire:click="setToday" 
                        class="px-4 py-3 bg-cyan-100 hover:bg-cyan-200 text-cyan-700 font-semibold rounded-xl transition">
                    Aujourd'hui
                </button>
                <button wire:click="setWeek" 
                        class="px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold rounded-xl transition">
                    Cette semaine
                </button>
                <button wire:click="setMonth" 
                        class="px-4 py-3 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold rounded-xl transition">
                    Ce mois
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes de résumé -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Chiffre d'affaires -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-green-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-white text-sm font-semibold mb-2">Chiffre d'affaires</h3>
                <p class="text-white text-3xl font-bold">{{ number_format($chiffreAffaires, 0, ',', ' ') }} F</p>
            </div>
            <div class="p-4 bg-gray-50">
                <p class="text-sm text-gray-600 flex items-center justify-between">
                    <span>Période sélectionnée</span>
                    @if($evolutionCA >= 0)
                        <span class="text-emerald-600 font-semibold">+{{ number_format($evolutionCA, 1) }}%</span>
                    @else
                        <span class="text-red-600 font-semibold">{{ number_format($evolutionCA, 1) }}%</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Nombre de ventes -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-white text-sm font-semibold mb-2">Nombre de ventes</h3>
                <p class="text-white text-3xl font-bold">{{ number_format($nombreVentes) }}</p>
            </div>
            <div class="p-4 bg-gray-50">
                <p class="text-sm text-gray-600">Transactions enregistrées</p>
            </div>
        </div>

        <!-- Panier moyen -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-white text-sm font-semibold mb-2">Panier moyen</h3>
                <p class="text-white text-3xl font-bold">{{ number_format($panierMoyen, 0, ',', ' ') }} F</p>
            </div>
            <div class="p-4 bg-gray-50">
                <p class="text-sm text-gray-600">Par transaction</p>
            </div>
        </div>

        <!-- Produits vendus -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas fa-ice-cream text-white text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-white text-sm font-semibold mb-2">Produits vendus</h3>
                <p class="text-white text-3xl font-bold">{{ number_format($produitsVendus) }}</p>
            </div>
            <div class="p-4 bg-gray-50">
                <p class="text-sm text-gray-600">Unités écoulées</p>
            </div>
        </div>
    </div>

    <!-- Graphiques et tableaux -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top produits -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-trophy text-amber-500"></i>
                Top 10 des produits
            </h2>

            @if($topProduits->count() > 0)
                <div class="space-y-3">
                    @foreach($topProduits as $index => $item)
                        <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-cyan-50 rounded-xl border border-gray-200">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-white font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 truncate">
                                    {{ $item->produit_nom }}
                                    @if($item->variant_nom && $item->variant_nom !== $item->produit_nom)
                                        <span class="text-gray-600 font-medium">- {{ $item->variant_nom }}</span>
                                    @endif
                                </h4>
                                <p class="text-sm text-gray-600">{{ $item->total_vendus }} unités vendues</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-emerald-600">{{ number_format($item->total_ca) }} F</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-chart-bar text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Aucune vente enregistrée pour cette période</p>
                </div>
            @endif
        </div>

        <!-- Ventes par catégorie -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-layer-group text-purple-500"></i>
                Ventes par catégorie
            </h2>

            @if($ventesParCategorie->count() > 0)
                <div class="space-y-3">
                    @foreach($ventesParCategorie as $item)
                        <div class="p-4 bg-gradient-to-r from-gray-50 to-purple-50 rounded-xl border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-800 flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full 
                                                @if($item->couleur == 'red') bg-red-500
                                                @elseif($item->couleur == 'orange') bg-orange-500
                                                @elseif($item->couleur == 'yellow') bg-yellow-500
                                                @elseif($item->couleur == 'green') bg-green-500
                                                @elseif($item->couleur == 'blue') bg-blue-500
                                                @elseif($item->couleur == 'purple') bg-purple-500
                                                @elseif($item->couleur == 'pink') bg-pink-500
                                                @else bg-gray-500
                                                @endif">
                                    </div>
                                    {{ $item->nom }}
                                </h4>
                                <span class="text-lg font-bold text-emerald-600">{{ number_format($item->total_ca) }} F</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-500"
                                        style="width: {{ min(100, $chiffreAffaires > 0 ? ($item->total_ca / $chiffreAffaires * 100) : 0) }}%">
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-600 shrink-0">
                                    {{ number_format(min(100, $chiffreAffaires > 0 ? ($item->total_ca / $chiffreAffaires * 100) : 0), 1) }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Aucune donnée disponible</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Performance des caissiers -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-users text-indigo-500"></i>
            Performance des caissiers
        </h2>

        @if($performanceCaissiers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($performanceCaissiers as $caissier)
                    <div class="p-6 bg-gradient-to-br from-gray-50 to-indigo-50 rounded-xl border border-indigo-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($caissier->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $caissier->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $caissier->nombre_ventes }} vente(s)</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total généré</span>
                            <span class="text-xl font-bold text-indigo-600">{{ number_format($caissier->total_ca) }} F</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Aucune vente enregistrée par les caissiers</p>
            </div>
        @endif
    </div>

    <!-- Évolution des ventes (graphique) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-chart-area text-cyan-500"></i>
            Évolution des ventes
        </h2>

        @if($evolutionVentes->count() > 0)
            <div class="space-y-2">
                @foreach($evolutionVentes as $jour)
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-semibold text-gray-600 w-24">
                            {{ \Carbon\Carbon::parse($jour->date)->format('d/m/Y') }}
                        </span>
                        <div class="flex-1 bg-gray-200 rounded-full h-8 flex items-center">
                            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-8 rounded-full flex items-center justify-end px-3" 
                                 style="width: {{ $chiffreAffaires > 0 ? ($jour->total / $chiffreAffaires * 100) : 0 }}%; min-width: 60px;">
                                <span class="text-white text-sm font-bold">{{ number_format($jour->total) }} F</span>
                            </div>
                        </div>
                        <span class="text-sm text-gray-600 w-20 text-right">{{ $jour->nombre }} vente(s)</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-chart-line text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Aucune donnée d'évolution disponible</p>
            </div>
        @endif
    </div>
</div>