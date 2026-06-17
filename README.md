# Application Web de Gestion des Terrains (GeoTerrain)

GeoTerrain est un projet de fin d'études complet (type BTS Développement Full Stack Web) conçu avec une architecture MVC sur-mesure en **PHP 8 pur (sans framework)**, **MySQL**, **Bootstrap 5**, **Vanilla JS** et **Chart.js**.

---

## 🚀 Fonctionnalités Clés

### 👥 Profils d'Utilisateurs
- **Administrateur** : Pilote l'activité via un Dashboard statistique enrichi (KPIs, revenus, volumes), effectue le CRUD complet des terrains (avec upload sécurisé), gère les rôles et comptes utilisateurs, statue sur les réservations (Approuver/Refuser), enregistre les règlements clients et consulte les graphiques mensuels.
- **Client** : S'enregistre et se connecte de façon sécurisée, effectue des recherches multicritères de terrains disponibles par ville, prix ou nom, soumet des réservations avec prévisualisation des prix en temps réel, annule ses demandes en attente et met à jour ses informations de profil.

### 🛡️ Sécurité & Algorithmes Métier
- **Date Overlap Prevention** : Algorithme interdisant les conflits de dates de location (double booking).
- **Protection Anti-Injection SQL** : Utilisation exclusive de PDO avec requêtes préparées.
- **Protection Anti-XSS** : Échappement HTML systématique sur l'affichage des variables utilisateurs.
- **Protection Anti-CSRF** : Génération et vérification systématique de jetons cryptographiques de session sur l'ensemble des formulaires de modification (POST).
- **Sécurisation des Mots de Passe** : Hachage fort Bcrypt (`password_hash()`, `password_verify()`).
- **Prévention de la fixation de session** : Régénération systématique de l'identifiant session après connexion.

---

## 📁 Structure du Projet

```
project/
├── app/
│   ├── controllers/      # Contrôleurs MVC (HomeController, AdminController, etc.)
│   ├── models/           # Modèles de tables SQL avec PDO (User, Terrain, etc.)
│   ├── views/            # Vues HTML (layouts réutilisables, client, admin, guest)
│   ├── middleware/       # Middlewares de session et rôles (AuthMiddleware)
│   └── helpers/          # Fonctions d'aide (functions.php) et Routeur personnalisé (Router.php)
├── config/
│   ├── database.php      # Connexion PDO à la base de données
│   └── routes.php        # Mappage des URLs vers les contrôleurs et actions
├── public/
│   ├── index.php         # Front Controller (Unique point d'entrée de l'app)
│   ├── .htaccess         # Réécriture d'URL pour index.php
│   └── uploads/          # Stockage des images de terrains importées
├── assets/
│   ├── css/              # Style CSS personnalisé (thème HSL premium, sidebar)
│   ├── js/               # Scripts JavaScript interactifs (sidebar mobile, alertes)
│   └── bootstrap/        # Bibliothèque Bootstrap 5 (CSS et JS)
├── database/
│   └── gestion_terrains.sql # Script SQL complet avec index, contraintes et seed data
├── tests/                # Suite de tests unitaires et de sécurité CLI
├── docs/                 # Documentation académique complète (Diagrammes UML, guides)
├── .htaccess             # Redirection automatique de la racine vers /public
└── README.md
```

---

## 📚 Documentation Détaillée

Consultez les documents ci-dessous pour l'évaluation académique et l'installation :
1. [Cahier des Charges & Spécifications Fonctionnelles](file:///c:/xampp/htdocs/riiiiiida/docs/functional_specs.md)
2. [Conception Technique & Diagrammes UML (Cas d'utilisations, Classes, Séquences, MLD)](file:///c:/xampp/htdocs/riiiiiida/docs/technical_specs.md)
3. [Guide d'Installation Local](file:///c:/xampp/htdocs/riiiiiida/docs/installation_guide.md)
4. [Guide d'Utilisation (Manuel Opérateur)](file:///c:/xampp/htdocs/riiiiiida/docs/user_guide.md)
5. [Plan de Test & Scénarios de Validation](file:///c:/xampp/htdocs/riiiiiida/docs/test_plan.md)

---

## 🛠️ Lancement des Tests Automatisés (CLI)

Ouvrez un terminal à la racine du projet et exécutez :

```bash
# Vérifier l'inscription, la détection des emails uniques, et le login
php tests/test_auth.php

# Vérifier l'algorithme anti-double-booking (chevauchement de dates)
php tests/test_reservations.php

# Vérifier le bon déroulement du cycle CRUD Terrains en base de données
php tests/test_crud.php

# Vérifier les mécanismes d'échappement XSS et de validation anti-CSRF
php tests/test_security.php
```
