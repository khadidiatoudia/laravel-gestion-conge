<x-mail::message>
# Demande de Congé Refusée

Bonjour {{ $agent->prenom }},

Nous vous informons que votre demande de congé a été **refusée** par l'administrateur RH.

**Détails de votre demande :**
- **Agent :** {{ $agent->nom_complet }}
- **Période demandée :** du {{ $conge->date_cessation_service->format('d/m/Y') }} au {{ $conge->date_reprise_service?->format('d/m/Y') ?? '-' }}
- **Nombre de jours demandés :** {{ $conge->jours_a_prendre }} jour(s)
- **Statut :** Refusée

Veuillez contacter l'administrateur RH pour plus de détails ou pour toute question.

<x-mail::button :url="url('/')">
Accéder à votre compte
</x-mail::button>

Cordialement,
**Service RH USSEIN**
</x-mail::message>
