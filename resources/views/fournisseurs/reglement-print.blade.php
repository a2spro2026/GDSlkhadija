<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Impression — {{ $reglement->reference }}</title>
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
    <h1>Règlement {{ $reglement->reference }}</h1>
    <p>GROUPE DLIMI SERVICES — {{ $reglement->date_reglement->format('d/m/Y') }}</p>
    <p><strong>Fournisseur :</strong> {{ $reglement->fournisseur->raison_sociale ?? '—' }}</p>
    <p><strong>Type :</strong> {{ $typeLabels[$reglement->type_reglement] ?? '' }} |
       <strong>Montant :</strong> {{ number_format($reglement->montant, 2, ',', ' ') }} DH |
       <strong>Statut :</strong> {{ $statutLabels[$reglement->statut] ?? '' }}</p>
    @if($reglement->numero)<p><strong>N° :</strong> {{ $reglement->numero }} — <strong>Bnq :</strong> {{ $reglement->banque }}</p>@endif
    @if($reglement->bonsAchats->isNotEmpty())
        <table>
            <thead><tr><th>N° Bon</th><th>Montant affecté</th></tr></thead>
            <tbody>
                @foreach($reglement->bonsAchats as $bon)
                    <tr><td>{{ $bon->numero_bon }}</td><td>{{ number_format($bon->pivot->montant_affecte, 2, ',', ' ') }} DH</td></tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <script>window.onload = () => window.print();</script>
</body>
</html>
