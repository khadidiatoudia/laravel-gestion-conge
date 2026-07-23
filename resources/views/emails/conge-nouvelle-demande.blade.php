<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Nouvelle Demande de Congé à Valider</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f7f4;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f7f4;padding:30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background-color:#142a1e;padding:24px 32px;">
                            <span style="color:#ffffff;font-size:18px;font-weight:bold;">USSEIN — Gestion des Congés</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <h1 style="color:#1c2621;font-size:22px;margin:0 0 16px;">Nouvelle Demande de Congé à Valider</h1>
                            <p style="color:#1c2621;font-size:15px;line-height:1.5;">Bonjour,</p>
                            <p style="color:#1c2621;font-size:15px;line-height:1.5;">
                                Une nouvelle demande de congé vient d'être déposée et attend votre validation.
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f7f4;border-radius:6px;margin:20px 0;">
                                <tr>
                                    <td style="padding:16px 20px;font-size:14px;color:#1c2621;">
                                        <strong>Agent :</strong> {{ $agent->nom_complet }}<br>
                                        <strong>Structure :</strong> {{ $agent->lieuAffectation->nom ?? 'Non renseignée' }}<br>
                                        <strong>Période demandée :</strong> du {{ $conge->date_cessation_service->format('d/m/Y') }} au {{ $conge->date_reprise_service?->format('d/m/Y') ?? '-' }}<br>
                                        <strong>Nombre de jours :</strong> {{ $conge->jours_a_prendre }} jour(s)<br>
                                        <strong>Solde restant de l'agent :</strong> {{ $agent->jours_restants }} jour(s)
                                    </td>
                                </tr>
                            </table>
                            <p style="text-align:center;margin:28px 0;">
                                <a href="{{ route('agents.show', $agent) }}" style="background-color:#e2a73b;color:#142a1e;text-decoration:none;padding:12px 28px;border-radius:6px;font-weight:bold;font-size:14px;display:inline-block;">Examiner la demande</a>
                            </p>
                            <p style="color:#5b6b62;font-size:13px;line-height:1.5;">Cordialement,<br><strong>Plateforme de Gestion des Congés — USSEIN</strong></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
