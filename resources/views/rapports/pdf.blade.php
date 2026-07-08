<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Congés {{ $annee }} - {{ $lieu->nom }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a3a5c; padding-bottom: 10px; }
        .header h2 { color: #1a3a5c; font-size: 14px; margin: 0 0 4px; }
        .header p { margin: 2px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1a3a5c; color: white; padding: 6px 8px; text-align: center; font-size: 10px; }
        td { padding: 5px 8px; border: 1px solid #ddd; }
        tr:nth-child(even) { background: #f8f9fa; }
        tfoot td { background: #e9ecef; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 9px; color: #999; text-align: right; }
    </style>
</head>
<body>
<div class="header">
    <h2>UNIVERSITÉ - TABLEAU DES CONGÉS {{ $annee }}</h2>
    <p><strong>Structure :</strong> {{ $lieu->nom }} ({{ $lieu->code }})</p>
    <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
</div>
<table>
    <thead>
        <tr>
            <th>N°</th><th>Nom et Prénom</th><th>Matricule</th>
            <th>Congés Dus</th><th>À Prendre</th><th>Absences</th><th>Restants</th>
            <th>Cessation</th><th>Reprise</th>
        </tr>
    </thead>
    <tbody>
        @foreach($agents as $i => $agent)
        <tr>
            <td style="text-align:center">{{ $i+1 }}</td>
            <td>{{ $agent->nom_complet }}</td>
            <td style="text-align:center">{{ $agent->matricule_solde }}</td>
            <td style="text-align:center;font-weight:bold">{{ $agent->conges_dus }}</td>
            <td style="text-align:center">{{ $agent->jours_a_prendre }}</td>
            <td style="text-align:center">{{ $agent->absences_deductibles_annee }}</td>
            <td style="text-align:center;font-weight:bold">{{ $agent->jours_restants }}</td>
            <td style="text-align:center">{{ $agent->date_cessation ? \Carbon\Carbon::parse($agent->date_cessation)->format('d/m/Y') : '-' }}</td>
            <td style="text-align:center">{{ $agent->date_reprise ? \Carbon\Carbon::parse($agent->date_reprise)->format('d/m/Y') : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right">TOTAUX</td>
            <td style="text-align:center">{{ $agents->sum(fn($a) => $a->conges_dus) }}</td>
            <td style="text-align:center">{{ $agents->sum(fn($a) => $a->jours_a_prendre) }}</td>
            <td style="text-align:center">{{ $agents->sum(fn($a) => $a->absences_deductibles_annee) }}</td>
            <td style="text-align:center">{{ $agents->sum(fn($a) => $a->jours_restants) }}</td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
</table>
<div class="footer">Document confidentiel - Université - Service des Ressources Humaines</div>
</body>
</html>
