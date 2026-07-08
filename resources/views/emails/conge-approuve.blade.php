<x-mail::message>
# Demande de Congé Approuvée

Bonjour {{ $agent->prenom }},

Votre demande de congé a été **approuvée** par l'administrateur RH.

**Détails de votre congé :**
- **Agent :** {{ $agent->nom_complet }}
- **Période :** du {{ $conge->date_cessation_service->format('d/m/Y') }} au {{ $conge->date_reprise_service?->format('d/m/Y') ?? '-' }}
- **Nombre de jours :** {{ $conge->jours_a_prendre }} jour(s)
- **Statut :** Approuvé

Vous pouvez consulter votre demande en accédant à votre compte.

<x-mail::button :url="url('/')">
Accéder à votre compte
</x-mail::button>

Cordialement,
**Service RH USSEIN**
</x-mail::message>
