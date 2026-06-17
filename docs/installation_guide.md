# Guide d'Installation de l'Application

Ce guide détaille les étapes nécessaires pour déployer et faire fonctionner l'**Application Web de Gestion des Terrains** dans un environnement de développement local (ex: XAMPP).

---

## 1. Prérequis Systèmes

- **Serveur Web** : Apache 2.4+ (avec module `mod_rewrite` activé).
- **Interpréteur** : PHP 8.0 ou supérieur.
- **Base de Données** : MySQL 5.7+ / MariaDB 10.3+.
- **Outil tout-en-un recommandé** : XAMPP ou WampServer.

---

## 2. Étapes de Déploiement

### Étape 2.1 : Emplacement des sources
1. Téléchargez et extrayez le projet dans le répertoire racine de votre serveur local :
   - Pour XAMPP : `C:\xampp\htdocs\riiiiiida\`
   - Pour WampServer : `C:\wamp64\www\riiiiiida\`

### Étape 2.2 : Importation de la base de données
1. Démarrez les modules **Apache** et **MySQL** depuis votre panneau de contrôle XAMPP.
2. Ouvrez votre navigateur et accédez à **phpMyAdmin** : `http://localhost/phpmyadmin/`
3. Créez une nouvelle base de données nommée `gestion_terrains` avec l'encodage `utf8mb4_unicode_ci`.
4. Cliquez sur l'onglet **Importer**.
5. Choisissez le fichier SQL situé dans le projet : `c:\xampp\htdocs\riiiiiida\database\gestion_terrains.sql`
6. Cliquez sur **Importer** (Go) en bas de la page. Les tables (`users`, `terrains`, `reservations`, `paiements`) et les données de test initiales seront créées.

### Étape 2.3 : Configuration de la base de données en PHP
Si vous utilisez les paramètres par défaut de XAMPP, aucune modification n'est requise. Le fichier `config/database.php` contient déjà les configurations standards :
- Hôte : `localhost`
- Base de données : `gestion_terrains`
- Utilisateur : `root`
- Mot de passe : `""` (vide)

Si votre configuration locale diffère (ex: mot de passe root), modifiez le fichier [config/database.php](file:///c:/xampp/htdocs/riiiiiida/config/database.php).

### Étape 2.4 : Permissions d'écriture (Uploads)
Vérifiez que le répertoire de téléversement des images de terrains existe et possède les permissions en écriture :
- Emplacement : `public/uploads/`
- (L'application crée automatiquement ce répertoire au premier démarrage s'il n'existe pas).

---

## 3. Accès à l'Application

1. Démarrez votre navigateur.
2. Accédez à l'URL suivante :
   - `http://localhost/riiiiiida/`
3. Le fichier `.htaccess` à la racine redirigera automatiquement vers le dossier public et lancera l'application.

---

## 4. Comptes de Test Pré-configurés

Pour faciliter l'évaluation et la soutenance de projet, les comptes suivants sont pré-chargés en base de données :

| Rôle | Adresse Email | Mot de passe | Description |
| :--- | :--- | :--- | :--- |
| **Administrateur** | `admin@terrains.com` | `admin123` | Accès complet aux tableaux de bord de gestion et rapports. |
| **Client** | `amine.rachidi@example.com` | `client123` | Profil client de test contenant un historique de réservations. |
| **Client 2** | `sara.benj@example.com` | `client123` | Profil client secondaire avec réservations. |
