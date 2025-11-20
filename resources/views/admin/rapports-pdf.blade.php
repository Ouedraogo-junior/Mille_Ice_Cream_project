<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Ventes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 100%;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0891b2;
        }
        
        .header h1 {
            color: #0891b2;
            font-size: 24pt;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 11pt;
        }
        
        .periode {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            border-left: 4px solid #0891b2;
        }
        
        .periode strong {
            color: #0891b2;
            font-size: 12pt;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 2px solid #e5e7eb;
            background: #f9fafb;
        }
        
        .stat-card h3 {
            font-size: 9pt;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .stat-card .value {
            font-size: 18pt;
            font-weight: bold;
            color: #0891b2;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #0891b2;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #0891b2;
            color: white;
        }
        
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
        }
        
        table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9pt;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        table tbody tr:hover {
            background: #f0f9ff;
        }
        
        .rank {
            display: inline-block;
            width: 25px;
            height: 25px;
            line-height: 25px;
            text-align: center;
            background: #0891b2;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 9pt;
        }
        
        .montant {
            color: #059669;
            font-weight: bold;
        }
        
        .caissier-card {
            display: inline-block;
            width: 32%;
            margin-right: 1.5%;
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #f9fafb;
            vertical-align: top;
        }
        
        .caissier-card:nth-child(3n) {
            margin-right: 0;
        }
        
        .caissier-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            font-size: 10pt;
        }
        
        .caissier-stats {
            color: #666;
            font-size: 9pt;
        }
        
        .caissier-ca {
            color: #0891b2;
            font-weight: bold;
            font-size: 11pt;
            margin-top: 5px;
        }
        
        .progress-bar {
            background: #e5e7eb;
            height: 20px;
            border-radius: 10px;
            margin: 8px 0;
            overflow: hidden;
        }
        
        .progress-fill {
            background: linear-gradient(to right, #0891b2, #06b6d4);
            height: 100%;
            color: white;
            text-align: right;
            padding-right: 8px;
            line-height: 20px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 9pt;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        
        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>📊 Rapport de Ventes</h1>
            <p>Glacier - Analyse des performances</p>
        </div>

        <!-- Période -->
        <div class="periode">
            <strong>Période :</strong> Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} 
            au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
        </div>

        <!-- Statistiques principales -->
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-card">
                    <h3>💰 Chiffre d'affaires</h3>
                    <div class="value">{{ number_format($chiffreAffaires, 0, ',', ' ') }} F</div>
                </div>
                <div class="stat-card">
                    <h3>🛒 Nombre de ventes</h3>
                    <div class="value">{{ number_format($nombreVentes) }}</div>
                </div>
                <div class="stat-card">
                    <h3>📈 Panier moyen</h3>
                    <div class="value">{{ number_format($panierMoyen, 0, ',', ' ') }} F</div>
                </div>
                <div class="stat-card">
                    <h3>🍦 Produits vendus</h3>
                    <div class="value">{{ number_format($produitsVendus) }}</div>
                </div>
            </div>
        </div>

        <!-- Top 10 Produits -->
        <div class="section">
            <h2 class="section-title">🏆 Top 10 des Produits</h2>
            @if($topProduits->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Produit</th>
                            <th>Variante</th>
                            <th style="text-align: center; width: 100px;">Quantité</th>
                            <th style="text-align: right; width: 120px;">CA Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProduits as $index => $item)
                        <tr>
                            <td><span class="rank">{{ $index + 1 }}</span></td>
                            <td><strong>{{ $item->produit_nom }}</strong></td>
                            <td>{{ $item->variant_nom && $item->variant_nom !== $item->produit_nom ? $item->variant_nom : '-' }}</td>
                            <td style="text-align: center;">{{ number_format($item->total_vendus) }}</td>
                            <td style="text-align: right;"><span class="montant">{{ number_format($item->total_ca, 0, ',', ' ') }} F</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Aucune donnée disponible</div>
            @endif
        </div>

        <!-- Ventes par catégorie -->
        <div class="section">
            <h2 class="section-title">📦 Ventes par Catégorie</h2>
            @if($ventesParCategorie->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Catégorie</th>
                            <th style="text-align: right; width: 150px;">CA Total</th>
                            <th style="text-align: right; width: 80px;">Part</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ventesParCategorie as $item)
                        <tr>
                            <td><strong>{{ $item->nom }}</strong></td>
                            <td style="text-align: right;"><span class="montant">{{ number_format($item->total_ca, 0, ',', ' ') }} F</span></td>
                            <td style="text-align: right;">{{ number_format($chiffreAffaires > 0 ? ($item->total_ca / $chiffreAffaires * 100) : 0, 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Aucune donnée disponible</div>
            @endif
        </div>

        <!-- Performance des caissiers -->
        <div class="section">
            <h2 class="section-title">👥 Performance des Caissiers</h2>
            @if($performanceCaissiers->count() > 0)
                @foreach($performanceCaissiers as $caissier)
                <div class="caissier-card">
                    <div class="caissier-name">{{ $caissier->name }}</div>
                    <div class="caissier-stats">{{ $caissier->nombre_ventes }} vente(s)</div>
                    <div class="caissier-ca">{{ number_format($caissier->total_ca, 0, ',', ' ') }} F</div>
                </div>
                @endforeach
            @else
                <div class="no-data">Aucune donnée disponible</div>
            @endif
        </div>

        <!-- Évolution des ventes -->
        <div class="section">
            <h2 class="section-title">📊 Évolution des Ventes</h2>
            @if($evolutionVentes->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 120px;">Date</th>
                            <th style="text-align: center; width: 100px;">Nb Ventes</th>
                            <th style="text-align: right;">CA Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evolutionVentes as $jour)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($jour->date)->format('d/m/Y') }}</strong></td>
                            <td style="text-align: center;">{{ $jour->nombre }}</td>
                            <td style="text-align: right;"><span class="montant">{{ number_format($jour->total, 0, ',', ' ') }} F</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Aucune donnée disponible</div>
            @endif
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>Rapport généré le {{ $dateGeneration }}</p>
            <p>© {{ date('Y') }} - Système de Gestion Glacier</p>
        </div>
    </div>
</body>
</html>