CREATE DATABASE IF NOT EXISTS gestion_terrains;
USE gestion_terrains;

/* =========================
   USERS
========================= */
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','client') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* =========================
   TERRAINS (FIXED)
========================= */
CREATE TABLE terrains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,

    emplacement VARCHAR(150) NOT NULL,
    localisation VARCHAR(255),

    prix DOUBLE NOT NULL,
    image VARCHAR(255),

    statut ENUM('disponible','reserve') DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* =========================
   RESERVATIONS
========================= */
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    terrain_id INT NOT NULL,

    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    montant_total DOUBLE,

    statut ENUM('en_attente','acceptee','refusee') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (terrain_id) REFERENCES terrains(id) ON DELETE CASCADE
);

/* =========================
   PAIEMENTS
========================= */
CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,

    montant DOUBLE NOT NULL,
    mode_paiement VARCHAR(50),
    statut VARCHAR(50),
    date_paiement DATE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
);

/* =========================
   MESSAGES
========================= */
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,

    sujet VARCHAR(255),
    message TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);