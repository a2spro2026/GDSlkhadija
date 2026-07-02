<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; margin: 24px; }
        h1 { font-size: 16px; margin: 0 0 4px; color: #071A35; }
        .meta { font-size: 11px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #071A35; color: #fff; font-size: 11px; }
        tr:nth-child(even) { background: #f8fafc; }
        @media print { body { margin: 12px; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:16px">
        <button onclick="window.print()" style="padding:8px 16px;cursor:pointer">Imprimer</button>
    </div>
    <h1>{{ $title }}</h1>
    <p class="meta">GROUPE DLIMI SERVICES — {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>Raison sociale</th>
                <th>Nom responsable</th>
                <th>Profil</th>
                <th>Contact</th>
                <th>E-mail</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fournisseurs as $f)
                <tr>
                    <td>{{ $f->raison_sociale }}</td>
                    <td>{{ $f->nom_responsable }}</td>
                    <td>{{ $f->profil ?? '—' }}</td>
                    <td>{{ $f->contact ?? '—' }}</td>
                    <td>{{ $f->email ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>window.onload = () => window.print();</script>
</body>
</html>
