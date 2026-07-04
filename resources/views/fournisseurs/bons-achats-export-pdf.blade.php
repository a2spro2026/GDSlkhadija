<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PDF — {{ $bonAchat->numero_bon }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #0f172a; margin: 0; padding: 20px; }
        .header { border-bottom: 3px solid #071A35; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; color: #071A35; }
        .header p { margin: 4px 0 0; color: #64748b; }
        .info { display: flex; gap: 24px; margin-bottom: 16px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #071A35; color: #fff; padding: 9px 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }
        .total { text-align: right; font-weight: 700; font-size: 14px; color: #1d4ed8; margin-top: 16px; }
        .no-print { position: fixed; top: 12px; right: 12px; }
        .no-print button { padding: 8px 14px; background: #dc2626; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Imprimer / Enregistrer PDF</button>
    </div>
    <div class="header">
        <h1>Bon d'achat {{ $bonAchat->numero_bon }}</h1>
        <p>GROUPE DLIMI SERVICES — {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
    <div class="info">
        <div><strong>Date :</strong> {{ $bonAchat->date_bon->format('d/m/Y') }}</div>
        <div><strong>Fournisseur :</strong> {{ $bonAchat->fournisseur->raison_sociale ?? '—' }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Réf</th>
                <th>Désignation</th>
                <th>Qté</th>
                <th>Prix U</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bonAchat->lignes as $l)
                <tr>
                    <td>{{ $l->reference ?? '—' }}</td>
                    <td><strong>{{ $l->designation }}</strong></td>
                    <td>{{ number_format($l->quantite, 2, ',', ' ') }}</td>
                    <td>{{ number_format($l->prix_unitaire, 2, ',', ' ') }} DH</td>
                    <td>{{ number_format($l->sous_total, 2, ',', ' ') }} DH</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total : {{ number_format($bonAchat->total, 2, ',', ' ') }} DH</p>
    <script>setTimeout(() => window.print(), 400);</script>
</body>
</html>
