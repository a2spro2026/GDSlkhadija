<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Impression — Dépôt IAM</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 24px; }
        h1 { font-size: 16px; color: #071A35; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #071A35; color: #fff; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:16px"><button onclick="window.print()">Imprimer</button></div>
    <h1>Dépôt IAM — {{ $article->designation }}</h1>
    <p>GROUPE DLIMI SERVICES</p>
    <table>
        <thead><tr><th>Réf</th><th>Désignation</th><th>Stock Init.</th><th>Entrée</th><th>Sortie</th><th>Stock Final</th><th>Statut</th><th>État</th></tr></thead>
        <tbody>
            <tr>
                <td>{{ $article->reference ?: '—' }}</td>
                <td>{{ $article->designation }}</td>
                <td>{{ number_format($article->stock_initial, 2, ',', ' ') }}</td>
                <td>{{ number_format($article->entree, 2, ',', ' ') }}</td>
                <td>{{ number_format($article->sortie, 2, ',', ' ') }}</td>
                <td><strong>{{ number_format($article->stockFinal(), 2, ',', ' ') }}</strong></td>
                <td>{{ $article->statutLabel() }}</td>
                <td>{{ $article->etatLabel() }}</td>
            </tr>
        </tbody>
    </table>
    <script>window.onload = () => window.print();</script>
</body>
</html>
