<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Global Congés {{ $annee }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #1a3a5c; padding-bottom: 8px; }
        .header h2 { color: #1a3a5c; font-size: 13px; }
        .section-title { background: #1a3a5c; color: white; padding: 5px 8px; font-weight: bold; margin-top: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { background: #2563eb; color: white; padding: 5px 6px; font-size: 9px; }
        td { padding: 4px 6px; border: 1px solid #ddd; }
        tr:nth-child(even) { background: #f8f9fa; }
        tfoot td { background: #e9ecef; font-weight: bold; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
<div class="header">
    <h2>RAPPORT GLOBAL DES CONGÉS {{ $annee }}</h2>
    <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
</div>
@foreach($lieux as $lieu)
@if($lieu->agents->count() > 0)
<div class="section-title">{{ strtoupper($lieu->nom) }} ({{ $lieu->code }})</div>
<table>
    <thead><tr><th>N°</th><th>Nom et Prénom</th><th>Matricule</th><th>Congés Dus</th><th>À Prendre</th><th>Absences</th><th>Restants</th><th>Cessation</th><th>Reprise</th></tr></thead>
    <tbody>
        @foreach($lieu->agents as $i => $agent)
        <tr>
            <td style="text-align:center">{{ $i+1 }}</td><td>{{ $agent->nom_complet }}</td><td>{{ $agent->matricule_solde }}</td>
            <td style="text-align:center;font-weight:bold">{{ $agent->conges_dus }}</td>
            <td style="text-align:center">{{ $agent->jours_a_prendre }}</td>
            <td style="text-align:center">{{ $agent->absences_deductibles_annee }}</td>
            <td style="text-align:center;font-weight:bold">{{ $agent->jours_restants }}</td>
            <td>{{ $agent->date_cessation ? \Carbon\Carbon::parse($agent->date_cessation)->format('d/m/Y') : '-' }}</td>
            <td>{{ $agent->date_reprise ? \Carbon\Carbon::parse($agent->date_reprise)->format('d/m/Y') : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right">TOTAL {{ $lieu->code }}</td>
            <td style="text-align:center">{{ $lieu->agents->sum(fn($a) => $a->conges_dus) }}</td>
            <td style="text-align:center">{{ $lieu->agents->sum(fn($a) => $a->jours_a_prendre) }}</td>
            <td style="text-align:center">{{ $lieu->agents->sum(fn($a) => $a->absences_deductibles_annee) }}</td>
            <td style="text-align:center">{{ $lieu->agents->sum(fn($a) => $a->jours_restants) }}</td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
</table>
@endif
@endforeach
</body>
</html>
