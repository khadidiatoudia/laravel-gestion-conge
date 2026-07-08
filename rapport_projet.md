# Rapport de Projet

## 1. Introduction

Ce rapport présente le projet de gestion des congés et absences pour l’institution USSEIN. L’objectif est de proposer une application Web simple et sécurisée permettant :

- la gestion des comptes utilisateurs et des agents,
- la consultation et la saisie des congés,
- l’approbation ou le refus des demandes par l’administrateur RH,
- la gestion des absences et des soldes de congés.

L’application est développée avec Laravel 12 et PHP 8.2, en utilisant Blade pour les vues et SQLite pour le stockage des données.

## 2. Contexte et besoins

### 2.1 Contexte

L’institution souhaite numériser la gestion des congés et des absences pour réduire les traitements manuels et améliorer la traçabilité des demandes.

### 2.2 Besoins

Les besoins identifiés sont :

- conserver une page d’accueil publique pour les visiteurs,
- proposer un accès sécurisé pour les gestionnaires et les administrateurs,
- distinguer les fonctionnalités accessibles à un admin et à un utilisateur lié à un agent,
- permettre l’association d’un compte utilisateur à un agent,
- autoriser les demandes de congés depuis le profil de l’agent,
- offrir un workflow d’approbation et de refus des congés,
- présenter le solde de congés disponibles et l’historique des demandes.

## 3. Architecture fonctionnelle

### 3.1 Structure du système

L’application est structurée autour de plusieurs entités principales :

- `User` : compte authentifié, peut être administrateur ou gestionnaire;
- `Agent` : profil métier lié à un agent, avec son solde de congés et ses informations RH;
- `Conge` : demande de congé enregistrée avec dates, durée, statut et type de déduction;
- `Absence` : enregistrement d’absence avec période et justification;
- `LieuAffectation` : structure ou UFR d’affectation de l’agent.

### 3.2 Rôles et autorisations

- Administrateur RH : accès complet à la gestion du personnel, aux rapports, au calendrier RH et à l’approbation des congés.
- Gestionnaire / utilisateur lié : accès à son tableau de bord personnel, à sa fiche agent si associée, et possibilité de saisir congé ou absence.

### 3.3 Routes principales

Les routes les plus importantes implémentées sont :

- `/` : page d’accueil publique;
- `/login`, `/register` : routes d’authentification pour les invités;
- `/dashboard` : tableau de bord admin;
- `/mon-compte` : tableau de bord utilisateur;
- `/agents` : gestion des agents et consultation des profils;
- `/agents/{agent}/conges/create` : saisie d’un congé pour un agent;
- `/conges/{conge}/statut` : modification du statut d’un congé;
- `/users` : liste des utilisateurs pour l’admin.

## 4. Réalisation technique

### 4.1 Authentification et redirections

L’authentification est gérée par un contrôleur personnalisé. Après connexion, l’utilisateur est redirigé vers :

- `admin.dashboard` si c’est un administrateur,
- `user.dashboard` si c’est un utilisateur lié.

La page d’inscription permet également, en option, d’associer directement un agent via son `matricule_solde`.

### 4.2 Association `User` -> `Agent`

Chaque utilisateur peut être lié à un `Agent`. Cette association permet de déterminer le solde de congés et d’autoriser l’accès aux fonctionnalités de saisie.

Un administrateur peut associer ou dissocier un compte utilisateur depuis la fiche agent. Un nouvel écran de gestion des utilisateurs a été ajouté pour afficher les emails disponibles et aider l’administration à retrouver l’utilisateur avant association.

### 4.3 Gestion des congés

La saisie d’un congé inclut :

- la date de cessation de service,
- le nombre de jours de congé demandés,
- l’option de déduction du solde ou non,
- le calcul de la date de reprise automatique.

Les demandes non administratives sont créées avec le statut `en_attente`. L’administrateur peut ensuite :

- approuver la demande,
- refuser la demande,
- supprimer la demande.

### 4.4 Interface et expérience utilisateur

Deux tableaux de bord distincts ont été mis en place :

- un tableau de bord admin avec des indicateurs globaux, des rapports par UFR, les congés en cours et les soldes critiques;
- un tableau de bord utilisateur avec le solde disponible, les congés demandés et l’historique personnel.

### 4.5 Sécurité et contrôle d’accès

Les middlewares `auth` et `admin` protègent les routes sensibles. Les actions liées à l’agent et aux congés vérifient que l’utilisateur connecté est bien autorisé à effectuer l’opération.

## 5. Tests et validation

### 5.1 Tests fonctionnels

Les scénarios principaux testés sont :

- inscription d’un utilisateur et association à un agent,
- connexion admin et utilisateur,
- création d’une demande de congé en attente,
- approbation et refus d’une demande depuis le dashboard admin,
- consultation de l’historique des congés et absences.

### 5.2 Validation manuelle

La validation a été réalisée au travers de parcours utilisateur :

- un agent peut visualiser son solde et déposer une demande,
- un administrateur peut accéder à la fiche agent, consulter les demandes et les approuver/refuser,
- la navigation est maintenue entre le dashboard, la gestion du personnel et les rapports.

## 6. Conclusion et perspectives

### 6.1 Bilan

Le projet fournit une base fonctionnelle solide pour la gestion des congés et des absences : authentification séparée, gestion des agents, workflow de demande et validation RH.

### 6.2 Améliorations possibles

Parmi les évolutions possibles :

- ajout d’un module de notifications par email,
- gestion des congés collectifs et des plannings d’absences,
- intégration d’un calendrier dynamique avec les jours fériés,
- export PDF des demandes et des états de congés,
- gestion des droits plus fine par type d’utilisateur.

### 6.3 Perspectives

Le système peut évoluer vers une solution RH complète en ajoutant des modules de suivi des temps, de planification des remplacements et de reporting avancé pour les responsables.
