-- Met à jour les 4 UFR génériques avec les vraies filières de l'USSEIN.
-- Les id restent inchangés, donc les agents déjà liés ne sont pas affectés.

UPDATE lieux_affectation SET code = 'SAEPAN', nom = "UFR Sciences Agronomiques, d'Élevage, de Pêche-Aquaculture et de Nutrition" WHERE code = 'UFR-ST';
UPDATE lieux_affectation SET code = 'SFI',    nom = "UFR Sciences Fondamentales et de l'Ingénieur" WHERE code = 'UFR-SEG';
UPDATE lieux_affectation SET code = 'SSE',    nom = "UFR Sciences Sociales et Environnementales" WHERE code = 'UFR-LSH';
UPDATE lieux_affectation SET code = 'SEJT',   nom = "UFR Sciences Économiques, Juridiques et Touristiques" WHERE code = 'UFR-SJP';
