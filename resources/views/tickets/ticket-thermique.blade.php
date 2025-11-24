<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $vente->numero_ticket }}</title>
    <style>
        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            width: 58mm;
            margin: 0 auto;
            padding: 1mm 2mm;
            background: #fff;
        }
        
        .container {
            max-width: 50mm;
            margin: 0 0;
        }
        
        /* HEADER */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            padding-bottom: 5px;
        }
        
        .header-left,
        .header-right {
            display: table-cell;
            vertical-align: middle;
        }
        
        .header-left {
            width: 35px;
        }
        
        .logo {
            width: 75px;
            height: 75px;
            max-width: 75px;
            max-height: 75px;
            object-fit: contain;
            display: block;
        }
        
        .header-right {
            padding-left: 5px;
        }
        
        .entreprise-nom {
            font-size: 15px;
            font-weight: 900;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 1px;
            margin: 0 0 2px 0;
            line-height: 1.1;
        }
        
        .entreprise-type {
            font-size: 12px;
            font-style: italic;
            margin-bottom: 3px;
            font-weight: normal;
            text-align: center;
        }
        
        .entreprise-adresse {
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            text-align: center;
        }
        
        /* INFO TICKET */
        .ticket-info {
            margin: 6px 0;
            padding: 4px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            font-size: 10px;
            text-align: center;
        }
        
        .ticket-info div {
            margin: 1px 0;
            
        }
        
        /* TABLEAU PRODUITS */
        .table-container {
            margin: 8px 0;
            
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead tr {
            border-bottom: 2px solid #000;
        }
        
        th {
            padding: 4px 2px;
            font-weight: 700;
            font-size: 10px;
            text-align: left;
        }
        
        th:first-child {
            width: 15%;
            font-size: 10px;
        }
        
        th:nth-child(2) {
            width: 45%;
            font-size: 10px;
        }
        
        th:nth-child(3) {
            width: 40%;
            text-align: right;
        }
        
        td {
            padding: 5px 2px;
            font-size: 10px;
            color: #333;
            border-bottom: 1px dotted #ccc;
        }
        
        td:first-child {
            text-align: left;
        }
        
        td:nth-child(2) {
            text-align: left;
            line-height: 1.2;
        }
        
        td:nth-child(3) {
            text-align: right;
            font-weight: 600;
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .variant-name {
            font-size: 7px;
            color: #666;
            font-style: italic;
        }
        
        /* TOTAL SECTION */
        .total-section {
            margin-top: 8px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .total-box {
            background: #d3d3d3;
            padding: 6px 12px;
            margin-bottom: 8px;
            width: 100%;
            text-align: center;
            border-radius: 3px;
        }
        
        .total-label {
            font-size: 8px;
            font-weight: normal;
            margin-bottom: 2px;
        }
        
        .total-amount {
            font-size: 13px;
            font-weight: bold;
        }
        
        /* PAIEMENT DETAILS */
        .paiement-details {
            width: 100%;
            font-size: 8px;
            margin-top: 6px;
        }
        
        .paiement-ligne {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px dotted #ddd;
        }
        
        .paiement-ligne:last-child {
            border-bottom: none;
        }
        
        .paiement-label {
            color: #333;
            font-weight: normal;
        }
        
        .paiement-montant {
            font-weight: 600;
            text-align: right;
        }
        
        /* FOOTER */
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
            padding-top: 8px;
            border-top: 1px solid #000;
        }
        
        .footer-message {
            margin-bottom: 6px;
            font-weight: normal;
            line-height: 1.3;
        }
        
        .fidelite {
            font-size: 9px;
            font-weight: bold;
            margin-top: 6px;
            padding: 4px;
            background: #f5f5f5;
            border-radius: 2px;
        }
        
        /* BOUTON IMPRESSION */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3b82f6;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    {{-- Bouton d'impression visible uniquement à l'écran --}}
    <button onclick="window.print()" class="print-button no-print">
        🖨️ Imprimer le ticket
    </button>

    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                @if($entreprise['logo'])
                    <img src="{{ asset($entreprise['logo']) }}" alt="Logo" class="logo">
                @endif
            </div>

            <div class="header-right">
                <div class="entreprise-nom">{{ $entreprise['nom'] }}</div>
                <div class="entreprise-type">{{ $entreprise['type'] ?? 'Glacier' }}</div>
                <div class="entreprise-adresse">
                    {{ $entreprise['adresse'] }}<br>
                    {{ $entreprise['code_postal'] ?? '' }} {{ $entreprise['ville'] ?? '' }}
                </div>
            </div>
        </div>

        {{-- Info ticket --}}
        <div class="ticket-info">
            <div><strong>Ticket N° {{ $vente->numero_ticket }}</strong></div>
            <div>{{ $vente->date_vente->format('d/m/Y à H:i') }}</div>
            @if($vente->caissier)
                <div>Caissier: {{ $vente->caissier->name }}</div>
            @endif
        </div>

        {{-- Tableau des produits --}}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Qté</th>
                        <th>Produit</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vente->details as $detail)
                    <tr>
                        <td>{{ $detail->quantite }}</td>
                        <td>
                            {{ $detail->produit->nom }}
                            @if($detail->variant)
                                <br><span class="variant-name">{{ $detail->variant->nom }}</span>
                            @endif
                            <br>
                            <span style="font-size: 7px; color: #666;">
                                {{ number_format($detail->prix_unitaire, 0, ',', ' ') }} F
                            </span>
                        </td>
                        <td>{{ number_format($detail->sous_total, 0, ',', ' ') }} F</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Section totaux --}}
        <div class="total-section">
            <div class="total-box">
                <div class="total-label">TOTAL FCFA</div>
                <div class="total-amount">{{ number_format($vente->total, 0, ',', ' ') }} F</div>
            </div>
            
            <div class="paiement-details">
                <div class="paiement-ligne">
                    @if($vente->mode_paiement === 'carte')
                        <span class="paiement-label">Payé par carte</span>
                    @elseif($vente->mode_paiement === 'mobile')
                        <span class="paiement-label">Payé par Mobile Money</span>
                    @else
                        <span class="paiement-label">Payé en espèces</span>
                    @endif
                    <span class="paiement-montant">{{ number_format($vente->montant ?? $vente->total, 0, ',', ' ') }} F</span>
                </div>
                
                @if($vente->mode_paiement === 'espece')
                    <div class="paiement-ligne">
                        <span class="paiement-label">Rendu en espèces</span>
                        <span class="paiement-montant">
                            {{ number_format($vente->monnaie_rendue ?? 0, 0, ',', ' ') }} F
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div class="footer-message">
                {{ $entreprise['message_footer'] ?? 'Merci de votre visite ! À bientôt !' }}
            </div>
            
            @if(isset($vente->points_fidelite) && $vente->points_fidelite > 0)
                <div class="fidelite">
                    Points fidélité : {{ $vente->points_fidelite }} points
                </div>
            @endif
        </div>
    </div>

    {{-- Script d'auto-impression --}}
    <script>
        // ✅ AUTO-IMPRESSION si paramètre ?auto=1 dans l'URL
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const autoPrint = urlParams.get('auto');
            
            if (autoPrint === '1') {
                console.log('🖨️ Auto-impression activée');
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html>