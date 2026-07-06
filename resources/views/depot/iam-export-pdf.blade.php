<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PDF — Dépôt IAM</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 24px; }
        h1 { font-size: 16px; color: #071A35; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #071A35; color: #fff; }
    </style>
</head>
<body>
    <h1>Dépôt IAM — {{ $article->designation }}</h1>
    <p>GROUPE DLIMI SERVICES — {{ now()->format('d/m/Y') }}</p>
    <table>
        <tr><th>Réf</th><td>{{ $article->reference ?: '—' }}</td></tr>
        <tr><th>Désignation</th><td>{{ $article->designation }}</td></tr>
        <tr><th>Stock Initial</th><td>{{ number_format($article->stock_initial, 2, ',', ' ') }}</td></tr>
        <tr><th>Entrée</th><td>{{ number_format($article->entree, 2, ',', ' ') }}</td></tr>
        <tr><th>Sortie</th><td>{{ number_format($article->sortie, 2, ',', ' ') }}</td></tr>
        <tr><th>Stock Final</th><td><strong>{{ number_format($article->stockFinal(), 2, ',', ' ') }}</strong></td></tr>
        <tr><th>Statut</th><td>{{ $article->statutLabel() }}</td></tr>
        <tr><th>État</th><td>{{ $article->etatLabel() }}</td></tr>
    </table>
</body>
</html>
