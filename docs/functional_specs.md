# Cahier des Charges & Spécifications Fonctionnelles

Ce document présente le Cahier des Charges Technique ainsi que les Spécifications Fonctionnelles détaillées de l'**Application Web de Gestion des Terrains**.

---

## 1. Cahier des Charges Technique

### 1.1 Contexte et Objectifs
Dans le cadre de la numérisation des infrastructures sportives et municipales, la gestion des plannings de terrains se fait souvent de manière manuelle, entraînant des erreurs de saisie, des doubles réservations et des pertes financières.
L'objectif de ce projet est de concevoir et développer une application web robuste et sécurisée permettant :
- Aux **Administrateurs** de gérer l'inventaire des terrains, les comptes utilisateurs, d'approuver ou refuser les demandes de réservation, et de piloter l'activité via un tableau de bord statistique.
- Aux **Clients** de s'inscrire, de rechercher des terrains disponibles avec des filtres multicritères, d'effectuer des réservations en ligne avec vérification instantanée de disponibilité et de suivre l'historique de leurs locations et paiements.

### 1.2 Public Cible et Acteurs
- **Administrateur (Gestionnaire du Complexe)** : Responsable opérationnel.
- **Client (Sportif individuel, Club, ou Entreprise)** : Utilisateur final louant les terrains.

### 1.3 Contraintes Techniques
- **Langage** : PHP 8 (Architecture MVC pure sans framework tiers type Laravel ou Symfony).
- **Base de données** : MySQL (Utilisation exclusive de l'API PDO avec requêtes préparées).
- **Interface utilisateur** : HTML5, CSS3, Bootstrap 5 (Responsive et ergonomie premium), Vanilla JS, et Chart.js pour les graphiques.
- **Sécurité** : Protection contre les failles OWASP majeures (XSS, CSRF, Injection SQL, Fixation de session).

---

## 2. Spécifications Fonctionnelles

### 2.1 Module d'Authentification & Profil
- **Inscription Client** : Formulaire d'inscription recueillant le Nom, Prénom, Email (unique), Téléphone et Mot de passe (avec confirmation et force minimale de 6 caractères).
- **Connexion** : Authentification sécurisée par email/mot de passe. Supporte l'option "Se souvenir de moi" via cookie.
- **Gestion de Profil** : Permet à l'utilisateur connecté de modifier ses informations personnelles ou de changer son mot de passe en fournissant son mot de passe actuel.
- **Déconnexion** : Fermeture sécurisée de la session et destruction des cookies de suivi.

### 2.2 Module de Gestion des Terrains (Admin)
- **Ajout de Terrain** : Formulaire permettant de spécifier le Nom, la Ville, le Prix horaire, la Surface, la Description et de téléverser une image (formats supportés : JPG, JPEG, PNG, WEBP, max 5 Mo).
- **Modification** : Mise à jour de toutes les caractéristiques d'un terrain, y compris le remplacement ou la conservation de son image.
- **Suppression** : Retrait définitif d'un terrain avec suppression automatique en cascade de ses réservations et paiements associés.

### 2.3 Module de Recherche et Réservation (Client)
- **Recherche & Filtrage** : Filtres multicritères sur la page publique et l'espace client :
  - Recherche textuelle par nom/description.
  - Sélection par ville (générée dynamiquement).
  - Fourchette de prix maximal.
  - Filtrage par statut de disponibilité.
- **Création de Réservation** : Formulaire de choix de dates (date_debut et date_fin).
- **Règles métier clés (Validation de disponibilité)** :
  1. *Interdiction des dates passées* : La date de début ne peut pas être antérieure à aujourd'hui.
  2. *Cohérence chronologique* : La date de fin doit être supérieure ou égale à la date de début.
  3. *Prévention des doubles réservations* : L'application vérifie en base de données qu'aucune autre réservation acceptée ou en attente ne chevauche la période demandée pour le terrain choisi.
- **Calcul du coût** : Calcul automatisé en temps réel du prix total : `nombre_de_jours * tarif_horaire` (le tarif horaire fait office de tarif journalier de référence dans ce modèle de réservation).

### 2.4 Module d'Approbation (Admin)
- **Validation** : L'administrateur visualise toutes les réservations soumises. Il peut :
  - **Accepter** la réservation : le terrain passe au statut "Réservé" pour cette période et le client est notifié.
  - **Refuser** la réservation : libère immédiatement le terrain pour d'autres demandes.
  - **Clôturer** (Terminer) : une fois la prestation effectuée et payée, remet le terrain disponible.

### 2.5 Module de Paiements & Chiffre d'Affaires
- **Enregistrement de paiement** : L'administrateur peut enregistrer un paiement pour une réservation acceptée en choisissant le mode de paiement (Espèces, Carte, Virement bancaire) et la date de transaction.
- **Facturation** : Clôture automatiquement la réservation après le paiement.

### 2.6 Module Dashboard & Statistiques (Chart.js)
- **Indicateurs clés de performance (KPI)** :
  - Chiffre d'affaires total accumulé.
  - Nombre total de terrains.
  - Nombre total de clients inscrits.
  - Nombre de demandes en attente d'approbation.
- **Représentations graphiques** :
  - Graphique linéaire : Évolution mensuelle du chiffre d'affaires.
  - Graphique à barres : Volume mensuel des réservations.
  - Graphique à barres horizontales : Popularité relative des terrains (nombre de locations).
