<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon d'achat — {{ $bonAchat->numero_bon }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; margin: 24px; }
        h1 { font-size: 16px; margin: 0 0 4px; color: #071A35; }
        .meta { font-size: 11px; color: #666; margin-bottom: 16px; }
        .info { margin-bottom: 16px; }
        .info span { margin-right: 24px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #071A35; color: #fff; font-size: 11px; }
        .total { text-align: right; font-weight: bold; margin-top: 12px; font-size: 13px; }
        @media print { body { margin: 12px; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:16px">
        <button onclick="window.print()" style="padding:8px 16px;cursor:pointer">Imprimer</button>
    </div>
    <h1>Bon d'achat — {{ $bonAchat->numero_bon }}</h1>
    <p class="meta">GROUPE DLIMI SERVICES — {{ now()->format('d/m/Y H:i') }}</p>
    <div class="info">
        <span><strong>Date :</strong> {{ $bonAchat->date_bon->format('d/m/Y') }}</span>
        <span><strong>Fournisseur :</strong> {{ $bonAchat->fournisseur->raison_sociale ?? '—' }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Réf</th>
                <th>Désignation</th>
                <th>Stock Init.</th>
                <th>Mesur</th>
                <th>Qté</th>
                <th>Prix U</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bonAchat->lignes as $l)
                <tr>
                    <td>{{ $l->reference ?? '—' }}</td>
                    <td>{{ $l->designation }}</td>
                    <td>{{ number_format($l->stock_initial ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ $l->mesure ?? '—' }}</td>
                    <td>{{ number_format($l->quantite, 2, ',', ' ') }}</td>
                    <td>{{ number_format($l->prix_unitaire, 2, ',', ' ') }} DH</td>
                    <td>{{ number_format($l->sous_total, 2, ',', ' ') }} DH</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total : {{ number_format($bonAchat->total, 2, ',', ' ') }} DH</p>
    <script>window.onload = () => window.print();</script>
</body>
</html>
