# Plan de Test & Scénarios de Validation

Ce document décrit la stratégie de test appliquée au projet, comment lancer la suite de tests automatisés en ligne de commande, et fournit la liste des tests de validation manuelle pour garantir la conformité fonctionnelle et sécuritaire de l'application.

---

## 1. Stratégie de Test

La validation du projet s'articule autour de deux approches :
1. **Tests unitaires et de sécurité automatisés** : Scripts PHP exécutés en ligne de commande (CLI) validant les algorithmes sensibles (modèles d'accès, règle de non-chevauchement des dates, filtrage XSS, jetons CSRF).
2. **Tests d'intégration fonctionnelle manuels** : Parcours utilisateur de validation de bout en bout sur l'interface graphique.

---

## 2. Exécution des Tests Automatisés (CLI)

Une suite de tests a été rédigée dans le répertoire `/tests/`. Pour lancer chaque script, ouvrez votre invite de commande dans la racine du projet et exécutez les commandes suivantes :

```bash
# Tester l'authentification (Inscription, Email Unique, Connexion et Nettoyage)
php tests/test_auth.php

# Tester la logique de chevauchement de dates de réservations (Double Booking)
php tests/test_reservations.php

# Tester le cycle CRUD complet sur la table Terrains
php tests/test_crud.php

# Tester l'échappement XSS et la génération/vérification des jetons anti-CSRF
php tests/test_security.php
```

---

## 3. Scénarios de Validation Manuelle (Checklist)

### 3.1 Module Authentification & Sécurité

| ID Test | Description | Procédure de Test | Résultat Attendu |
| :--- | :--- | :--- | :--- |
| **SEC-01** | Tentative de connexion incorrecte | Saisir un email existant avec un mauvais mot de passe. | Message d'erreur "Adresse email ou mot de passe incorrect." |
| **SEC-02** | Validation CSRF | Tenter de soumettre le formulaire d'inscription en altérant le jeton caché. | Blocage par l'application et affichage d'un message d'échec CSRF. |
| **SEC-03** | Injection SQL | Tenter de saisir `' OR 1=1 --` dans les champs d'authentification ou filtres. | Aucun accès autorisé. Les requêtes préparées PDO neutralisent l'injection. |
| **SEC-04** | Protection XSS | Insérer `<script>alert('xss')</script>` dans le nom d'un terrain. | Le script est affiché textuellement à l'écran sans s'exécuter. |

### 3.2 Gestion des Terrains & Téléversement

| ID Test | Description | Procédure de Test | Résultat Attendu |
| :--- | :--- | :--- | :--- |
| **CRUD-01**| Ajout terrain sans nom/ville | Soumettre le formulaire d'ajout en laissant un champ obligatoire vide. | Blocage par la validation HTML5 et PHP. |
| **CRUD-02**| Ajout terrain avec image volumineuse | Essayer d'importer une image de terrain de 7 Mo. | Message d'erreur spécifiant la limite de 5 Mo. |
| **CRUD-03**| Format d'image invalide | Importer un fichier `.pdf` ou `.docx` dans le champ image. | Refus avec message "Format d'image non supporté." |

### 3.3 Réservations & Logique Métier

| ID Test | Description | Procédure de Test | Résultat Attendu |
| :--- | :--- | :--- | :--- |
| **RES-01** | Réservation dans le passé | Tenter de réserver à une date antérieure à la date du jour. | Bloqué : "La date de début ne peut pas être dans le passé." |
| **RES-02** | Dates inversées | Sélectionner une date de fin antérieure à la date de début. | Bloqué par l'application : message de validation. |
| **RES-03** | Double Réservation | Tenter de réserver un terrain déjà occupé pour la même période. | Rejet immédiat : "Ce terrain est déjà réservé pour les dates sélectionnées." |

### 3.4 Enregistrement des Règlements

| ID Test | Description | Procédure de Test | Résultat Attendu |
| :--- | :--- | :--- | :--- |
| **PAY-01** | Enregistrement paiement | Valider une réservation Admin. Aller dans Paiements, choisir la réservation, spécifier le mode et valider. | Le montant s'ajoute au Chiffre d'Affaires du Dashboard. La réservation passe à "Terminée". |
