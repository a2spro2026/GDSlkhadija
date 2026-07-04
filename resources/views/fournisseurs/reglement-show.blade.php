<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Règlement — {{ $reglement->reference }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 24px; color: #111; }
        h1 { font-size: 16px; color: #071A35; margin: 0 0 8px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #071A35; color: #fff; font-size: 11px; }
        .statut { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Règlement {{ $reglement->reference }}</h1>
    <p>GROUPE DLIMI SERVICES — {{ now()->format('d/m/Y H:i') }}</p>
    <div class="grid">
        <div><strong>Date :</strong> {{ $reglement->date_reglement->format('d/m/Y') }}</div>
        <div><strong>Fournisseur :</strong> {{ $reglement->fournisseur->raison_sociale ?? '—' }}</div>
        <div><strong>Type :</strong> {{ $typeLabels[$reglement->type_reglement] ?? '' }}</div>
        <div><strong>Montant :</strong> {{ number_format($reglement->montant, 2, ',', ' ') }} DH</div>
        <div><strong>N° :</strong> {{ $reglement->numero ?? '—' }}</div>
        <div><strong>Banque :</strong> {{ $reglement->banque ?? '—' }}</div>
        <div><strong>Nom tiré :</strong> {{ $reglement->nom_tire ?? '—' }}</div>
        <div><strong>Date décaiss. :</strong> {{ $reglement->date_decaissement?->format('d/m/Y') ?? '—' }}</div>
        <div><strong>Statut :</strong> <span class="statut">{{ $statutLabels[$reglement->statut] ?? '' }}</span></div>
    </div>
    @if($reglement->bonsAchats->isNotEmpty())
        <h2 style="font-size:14px">Bons affectés</h2>
        <table>
            <thead><tr><th>N° Bon</th><th>Montant affecté</th></tr></thead>
            <tbody>
                @foreach($reglement->bonsAchats as $bon)
                    <tr>
                        <td>{{ $bon->numero_bon }}</td>
                        <td>{{ number_format($bon->pivot->montant_affecte, 2, ',', ' ') }} DH</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
