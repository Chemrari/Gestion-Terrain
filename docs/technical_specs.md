# Spécifications Techniques & Diagrammes UML

Ce document présente l'architecture logicielle, le modèle de base de données, la sécurité et les diagrammes de conception UML (cas d'utilisation, classes, séquences) de l'**Application Web de Gestion des Terrains**.

---

## 1. Architecture Logicielle (MVC Custom)

L'application est construite sur un patron de conception **Modèle-Vue-Contrôleur (MVC)** personnalisé en PHP pur :

```
                        +-------------------+
                        |   Navigateur      |
                        +---------+---------+
                                  |
                           Requêtes HTTP
                                  |
                                  v
                        +---------+---------+
                        |  public/index.php |  <--- Point d'entrée (Front Controller)
                        +---------+---------+
                                  |
                                  v
                        +---------+---------+
                        |    Router.php     |  <--- Route les URIs selon config/routes.php
                        +---------+---------+
                                  |
                                  v
                      [ Middlewares de sécurité ] (Vérification de session & rôle)
                                  |
                                  v
                        +---------+---------+
                        |   Controllers     |  <--- Orchestre les requêtes (app/controllers/)
                        +----+---------+----+
                             |         |
            Interagit avec   |         |  Génère le code HTML
                             v         v
                        +----+----+  +----+----+
                        |  Models |  |  Views  |
                        +---------+  +---------+
                       (app/models)  (app/views)
                             |
                      Accès PDO SQL
                             v
                        +---------+
                        | Base de |
                        | Données |
                        +---------+
```

### 1.1 Composants Principaux
- **Front Controller (`public/index.php`)** : Initialise la session, configure l'autoloader dynamique des classes, inclut les fonctions d'aide globales et lance le dispatching du routeur.
- **Routeur (`app/helpers/Router.php`)** : Analyse l'URI d'entrée, la normalise (même en cas de déploiement dans un sous-dossier comme `/riiiiiida/public/`), applique les middlewares configurés et instancie l'action du contrôleur approprié.
- **Middlewares (`app/middleware/AuthMiddleware.php`)** : Sécurise les routes en vérifiant le statut de connexion de la session utilisateur et le rôle requis (`admin` ou `client`).
- **Helpers (`app/helpers/functions.php`)** : Contient les fonctions utilitaires pour la sécurité XSS, la génération et vérification des jetons CSRF, et la gestion des messages flash (toasts).

---

## 2. Sécurité de l'Application

1. **Injections SQL** : Prévenues de manière systématique par l'utilisation de requêtes préparées avec l'extension PDO. Les paramètres utilisateur sont liés via des variables nommées (ex: `:email`, `:id`) empêchant toute exécution détournée du code SQL.
2. **Failles XSS (Cross-Site Scripting)** : Neutralisées par l'utilisation systématique de la fonction d'échappement `e($string)` (alias de `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`) lors de l'affichage des variables en provenance de la base de données.
3. **Failles CSRF (Cross-Site Request Forgery)** : Bloquées sur l'ensemble des formulaires de modification (POST) par la génération d'un jeton de session aléatoire unique (`csrf_token()`) vérifié côté contrôleur avant toute action d'écriture.
4. **Hachage des Mots de Passe** : Chiffrement cryptographique fort via l'algorithme Bcrypt à l'aide des fonctions natives de PHP `password_hash()` et `password_verify()`.
5. **Fixation de session** : Prévenue lors de la connexion réussie d'un utilisateur par la régénération forcée de l'identifiant de session (`session_regenerate_id(true)`).

---

## 3. Diagrammes de Conception UML

### 3.1 Diagramme des Cas d'Utilisation (Use Case)

```mermaid
flowchart LR
    Client[Client]
    Admin[Admin]

    subgraph S["Application de Gestion de Terrains"]
        UC1["S'inscrire / Se connecter"]
        UC2["Consulter les terrains (Filtres)"]
        UC3["Réserver un terrain"]
        UC4["Annuler une réservation"]
        UC5["Gérer l'inventaire des terrains (CRUD)"]
        UC6["Valider les réservations (Accepter/Refuser)"]
        UC7["Enregistrer un paiement"]
        UC8["Visualiser les rapports statistiques"]
    end

    Client --> UC1
    Client --> UC2
    Client --> UC3
    Client --> UC4

    Admin --> UC1
    Admin --> UC5
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8
```

### 3.2 Diagramme de Classes

```mermaid
classDiagram
    class Database {
        +conn : PDO
        +connect() : PDO
    }
    
    class User {
        -conn : PDO
        -table : string
        +register(nom, prenom, email, telephone, password, role) : bool
        +emailExists(email, excludeId) : bool
        +getByEmail(email) : array
        +getById(id) : array
        +update(id, nom, prenom, email, telephone) : bool
        +updatePassword(id, hashedPassword) : bool
        +updateRole(id, role) : bool
        +delete(id) : bool
        +getAll() : array
        +countClients() : int
    }

    class Terrain {
        -conn : PDO
        -table : string
        +add(nom, ville, prix, surface, description, image, statut) : bool
        +update(id, nom, ville, prix, surface, description, image, statut) : bool
        +delete(id) : bool
        +getById(id) : array
        +getAll() : array
        +search(search, ville, statut, max_prix) : array
        +countTotal() : int
        +getDistinctVilles() : array
        +updateStatus(id, statut) : bool
    }

    class Reservation {
        -conn : PDO
        -table : string
        +add(user_id, terrain_id, date_debut, date_fin, montant_total, statut) : int
        +isAvailable(terrain_id, date_debut, date_fin, excludeId) : bool
        +getById(id) : array
        +getByUserId(user_id) : array
        +getAllWithDetails() : array
        +updateStatus(id, statut) : bool
        +countTotal() : int
        +countByStatus(statut) : int
        +getMonthlyStats() : array
        +getPopularTerrains(limit) : array
    }

    class Paiement {
        -conn : PDO
        -table : string
        +add(reservation_id, montant, mode_paiement, date_paiement, statut) : bool
        +getByReservationId(reservation_id) : array
        +getAllWithDetails() : array
        +sumTotalRevenue() : double
        +getMonthlyRevenue() : array
    }

    User "1" --> "*" Reservation : "effectue"
    Terrain "1" --> "*" Reservation : "est loué"
    Reservation "1" --> "0..1" Paiement : "génère"
    Database --> User : "injecte conn"
    Database --> Terrain
    Database --> Reservation
    Database --> Paiement
```

### 3.3 Diagramme de Séquence (Vérification et Création de Réservation)

```mermaid
sequenceDiagram
    autonumber
    actor Client
    participant Controller as ClientController
    participant ModelRes as Reservation
    participant ModelTerrain as Terrain
    participant DB as MySQL Database

    Client->>Controller: POST client/reserve (terrain_id, date_debut, date_fin)
    activate Controller
    
    Controller->>ModelTerrain: getById(terrain_id)
    activate ModelTerrain
    ModelTerrain->>DB: SELECT * FROM terrains WHERE id = ?
    DB-->>ModelTerrain: Données du terrain
    deactivate ModelTerrain
    
    Controller->>ModelRes: isAvailable(terrain_id, date_debut, date_fin)
    activate ModelRes
    ModelRes->>DB: SELECT COUNT(*) FROM reservations WHERE... (dates overlap)
    DB-->>ModelRes: Résultat (0 = disponible, >0 = réservé)
    deactivate ModelRes
    
    alt Pas de recouvrement (disponible)
        Controller->>ModelRes: add(user_id, terrain_id, date_debut, date_fin, montant_total)
        activate ModelRes
        ModelRes->>DB: INSERT INTO reservations...
        DB-->>ModelRes: LastInsertId
        deactivate ModelRes
        Controller-->>Client: Redirection avec message succès (Flash)
    else Terrain déjà réservé (conflit)
        Controller-->>Client: Redirection avec message d'erreur
    end
    
    deactivate Controller
```

---

## 4. Modèle Logique de Données (MLD)

La base de données relationnelle est articulée autour de 4 tables clés indexées :

1. **users** (`id` INT PK, `nom` VARCHAR, `prenom` VARCHAR, `email` VARCHAR UNIQUE, `telephone` VARCHAR, `password` VARCHAR, `role` ENUM, `created_at` TIMESTAMP)
2. **terrains** (`id` INT PK, `nom` VARCHAR, `ville` VARCHAR, `prix` DOUBLE, `surface` DOUBLE, `description` TEXT, `image` VARCHAR, `statut` ENUM, `created_at` TIMESTAMP)
3. **reservations** (`id` INT PK, `user_id` INT FK -> users(id) ON DELETE CASCADE, `terrain_id` INT FK -> terrains(id) ON DELETE CASCADE, `date_debut` DATE, `date_fin` DATE, `montant_total` DOUBLE, `statut` ENUM, `created_at` TIMESTAMP)
4. **paiements** (`id` INT PK, `reservation_id` INT FK -> reservations(id) ON DELETE CASCADE, `montant` DOUBLE, `mode_paiement` VARCHAR, `date_paiement` DATE, `statut` ENUM, `created_at` TIMESTAMP)
