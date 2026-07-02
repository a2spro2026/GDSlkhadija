<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export PDF — Fournisseurs</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #0f172a; margin: 0; padding: 20px; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #071A35;
        }
        .header h1 { margin: 0; font-size: 18px; color: #071A35; }
        .header p { margin: 4px 0 0; color: #64748b; font-size: 11px; }
        .brand { text-align: right; font-weight: 700; color: #0F4C81; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #071A35;
            color: #fff;
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        td { padding: 9px 8px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) td { background: #f8fafc; }
        .footer { margin-top: 16px; font-size: 9px; color: #94a3b8; text-align: center; }
        .no-print {
            position: fixed; top: 12px; right: 12px; z-index: 10;
            display: flex; gap: 8px;
        }
        .no-print button, .no-print a {
            padding: 8px 14px; font-size: 12px; font-weight: 600;
            border-radius: 6px; cursor: pointer; text-decoration: none;
            border: none;
        }
        .btn-print { background: #7c3aed; color: #fff; }
        .btn-pdf { background: #dc2626; color: #fff; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Imprimer / Enregistrer en PDF</button>
    </div>

    <div class="header">
        <div>
            <h1>Liste des fournisseurs</h1>
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
        <div class="brand">GROUPE DLIMI SERVICES</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Raison sociale</th>
                <th>Nom responsable</th>
                <th>Profil</th>
                <th>Contact</th>
                <th>E-mail</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fournisseurs as $i => $f)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $f->raison_sociale }}</strong></td>
                    <td>{{ $f->nom_responsable }}</td>
                    <td>{{ $f->profil ?? '—' }}</td>
                    <td>{{ $f->contact ?? '—' }}</td>
                    <td>{{ $f->email ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;padding:24px">Aucun fournisseur</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">© {{ date('Y') }} GROUPE DLIMI SERVICES — Export PDF</p>

    <script>
        window.onload = function () {
            setTimeout(() => window.print(), 400);
        };
    </script>
</body>
</html>
