<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $vente->numero_ticket }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: A5;
            margin: 15mm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        
        /* HEADER */
.header {
    display: table;
    width: 100%;
    margin-bottom: 10px;
    padding-bottom: 5px;
}

.header-left,
.header-right {
    display: table-cell;
    vertical-align: middle; /* 🎯 CENTRE parfaitement en hauteur */
}

/* Force une largeur fixe pour éviter que DOMPDF balade tout */
.header-left {
    width: 90px;
}

/* LOGO : taille *réelle* respectée par DOMPDF */
.logo {
    width: 80px;
    height: 80px;
    max-width: 80px;
    max-height: 80px;
    object-fit: contain;
    display: block;
}
.header-left img {
    display: block;
}


        
        .header-info {
            flex-grow: 1;
        }
        
        .entreprise-nom {
            font-size: 32px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 0 0 5px 0;
        }
        
        .entreprise-type {
            font-size: 20px;
            font-style: italic;
            margin-bottom: 10px;
            font-weight: normal;
        }
        
        .entreprise-adresse {
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        
        .table-container {
            margin: 10px 0 25px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead tr {
            border-bottom: 2px solid #000;
        }
        
        th {
            padding: 10px 15px;
            font-weight: 700;
            font-size: 16px;
            text-align: left;
        }
        
        th:first-child {
            width: 8%;
        }
        
        th:nth-child(2) {
            width: 50%;
        }
        
        th:nth-child(3) {
            width: 21%;
            text-align: right;
        }
        
        th:nth-child(4) {
            width: 21%;
            text-align: right;
        }
        
        td {
            padding: 15px 15px;
            font-size: 16px;
            color: #333;
        }
        
        td:first-child {
            text-align: left;
        }
        
        td:nth-child(2) {
            text-align: left;
        }
        
        td:nth-child(3) {
            text-align: right;
        }
        
        td:nth-child(4) {
            text-align: right;
            font-weight: 500;
        }
        
        .total-section {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .total-box {
            background: #d3d3d3;
            padding: 15px 40px;
            margin-bottom: 25px;
            min-width: 280px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .total-label {
            font-size: 16px;
            font-weight: normal;
            margin-bottom: 5px;
        }
        
        .total-amount {
            font-size: 24px;
            font-weight: bold;
        }
        
        .paiement-details {
            width: 100%;
            max-width: 500px;
            font-size: 16px;
            margin-top: 15px;
        }
        
        .paiement-ligne {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        
        .paiement-label {
            color: #333;
            font-weight: normal;
        }
        
        .paiement-montant {
            font-weight: 500;
            min-width: 100px;
            text-align: right;
        }
        
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 16px;
            padding-top: 30px;
        }
        
        .footer-message {
            margin-bottom: 20px;
            font-weight: normal;
        }
        
        .fidelite {
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
    <div class="header-left">
        @if($entreprise['logo'])
            <img src="{{ public_path($entreprise['logo']) }}" alt="Logo" class="logo">
        @endif
    </div>

    <div class="header-right">
        <div class="entreprise-nom">{{ $entreprise['nom'] }}</div>
        <div class="entreprise-type">{{ $entreprise['type'] ?? 'Glacier' }}</div>
        <div class="entreprise-adresse">
            {{ $entreprise['adresse'] }}<br>
            {{ $entreprise['code_postal'] ?? '' }}, {{ $entreprise['ville'] ?? '' }}
        </div>
    </div>
</div>


    {{-- Tableau des produits --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Qtt</th>
                    <th>Produit</th>
                    <th>Prix<br>Initial</th>
                    <th>Prix<br>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vente->details as $detail)
                <tr>
                    <td>{{ $detail->quantite }}</td>
                    <td>{{ $detail->produit->nom }}</td>
                    <td>{{ number_format($detail->prix_unitaire, 2, ',', ' ') }} f</td>
                    <td>{{ number_format($detail->sous_total, 2, ',', ' ') }} f</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Section totaux --}}
    <div class="total-section">
        <div class="total-box">
            <div class="total-label">TOTAL FCFA</div>
            <div class="total-amount">{{ number_format($vente->total, 2, ',', ' ') }} FCFA</div>
        </div>
        
        <div class="paiement-details">
            <div class="paiement-ligne">
                @if($vente->mode_paiement === 'carte')
                <span class="paiement-label">Payé par carte</span>
                @elseif($vente->mode_paiement === 'mobile')
                <span class="paiement-label">Payé par Mobile Money</span>
                @else
                <span class="paiement-label">Payé en espèce</span>
                @endif
                <span class="paiement-montant">{{ number_format($vente->montant_recu ?? $vente->total, 2, ',', ' ') }} F</span>
            </div>
            <div class="paiement-ligne">
                <span class="paiement-label">Rendu en espèces</span>
                <span class="paiement-montant">{{ number_format(($vente->montant_recu ?? $vente->total) - $vente->total, 2, ',', ' ') }} F</span>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-message">
            {{ $entreprise['message_footer'] ?? 'Merci de votre visite ! A bientôt !' }}
        </div>
        
        @if(isset($vente->points_fidelite))
        <div class="fidelite">
            Points fidélité : {{ $vente->points_fidelite }} points
        </div>
        @endif
    </div>
</body>
</html>