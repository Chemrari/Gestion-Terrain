# GeoTerrain

GeoTerrain est une application web de gestion des terrains de sport développée en **PHP 8 pur**, **MySQL**, **Bootstrap 5**, **Vanilla JS** et **Chart.js**.

## Aperçu
- Authentification sécurisée pour **administrateurs** et **clients**
- CRUD complet des terrains avec upload d’images
- Réservations avec prévention des chevauchements de dates
- Gestion des paiements, profils et statistiques
- Protection contre les injections SQL, XSS et CSRF

## Technologies
- PHP 8
- MySQL / PDO
- Bootstrap 5
- JavaScript vanilla
- Chart.js

## Prérequis
- PHP 8+
- MySQL / MariaDB
- Serveur local comme XAMPP, WAMP ou Laragon

## Installation
1. Clonez le projet dans votre dossier web local.
2. Importez `database/gestion_terrains.sql` dans votre base MySQL.
3. Configurez la connexion BD dans `config/database.php`.
4. Assurez-vous que le dossier `public/uploads/` est accessible en écriture.
5. Ouvrez l’application via le dossier `public/`.

## Lancement
- Point d’entrée public : `public/index.php`
- Redirection racine : `.htaccess`

## Tests
Exécutez les scripts CLI depuis la racine du projet :

```bash
php tests/test_auth.php
php tests/test_reservations.php
php tests/test_crud.php
php tests/test_security.php
```

## Structure
```text
app/          Contrôleurs, modèles, vues, helpers, middleware
assets/       CSS, JS et ressources front-end
config/       Connexion base de données et routes
database/     Script SQL du projet
docs/         Documentation fonctionnelle et technique
public/       Point d’entrée web et uploads
tests/        Tests CLI
```

## Documentation
- `docs/functional_specs.md`
- `docs/technical_specs.md`
- `docs/installation_guide.md`
- `docs/user_guide.md`
- `docs/test_plan.md`

## Notes
- Les fichiers envoyés dans `public/uploads/` ne doivent pas être versionnés.
- Les secrets et variables locales doivent rester hors du dépôt via `.gitignore`.

