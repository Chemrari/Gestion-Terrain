SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS gestion_terrains CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_terrains;

-- Drop tables if they exist
DROP TABLE IF EXISTS paiements;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS terrains;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS messages;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes for users
CREATE INDEX idx_user_email ON users(email);

-- --------------------------------------------------------
-- Table: terrains
-- --------------------------------------------------------
CREATE TABLE terrains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    prix DOUBLE NOT NULL,
    surface DOUBLE NOT NULL,
    description TEXT DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    statut ENUM('disponible', 'reserve') NOT NULL DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes for terrains
CREATE INDEX idx_terrain_ville ON terrains(ville);
CREATE INDEX idx_terrain_statut ON terrains(statut);

-- --------------------------------------------------------
-- Table: reservations
-- --------------------------------------------------------
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    terrain_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    montant_total DOUBLE NOT NULL,
    statut ENUM('en_attente', 'acceptee', 'refusee', 'terminee') NOT NULL DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (terrain_id) REFERENCES terrains(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes for reservations
CREATE INDEX idx_reservation_user ON reservations(user_id);
CREATE INDEX idx_reservation_terrain ON reservations(terrain_id);
CREATE INDEX idx_reservation_statut ON reservations(statut);

-- --------------------------------------------------------
-- Table: paiements
-- --------------------------------------------------------
CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    montant DOUBLE NOT NULL,
    mode_paiement VARCHAR(50) NOT NULL, -- 'carte', 'espece', 'virement'
    date_paiement DATE NOT NULL,
    statut ENUM('paye', 'rembourse', 'annule') NOT NULL DEFAULT 'paye',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes for paiements
CREATE INDEX idx_paiement_reservation ON paiements(reservation_id);
CREATE INDEX idx_paiement_date ON paiements(date_paiement);

-- --------------------------------------------------------
-- Seed Data
-- --------------------------------------------------------

-- Admin (password: admin123)
-- Clients (password: client123)
INSERT INTO users (nom, prenom, email, telephone, password, role) VALUES
('Admin', 'System', 'admin@terrains.com', '0600000001', '$2y$10$0r40gsxm3qDGe.tDccbOY.aJ8obpzqy0pucOsL.qb8WSwmQixrpCm', 'admin'),
('Rachidi', 'Amine', 'amine.rachidi@example.com', '0612345678', '$2y$10$gAyARiTYC6KLEA/9vhnOPu9amxYxo9DbJkky.qAEz2JlMdvJADwZy', 'client'),
('Benjelloun', 'Sara', 'sara.benj@example.com', '0676543210', '$2y$10$gAyARiTYC6KLEA/9vhnOPu9amxYxo9DbJkky.qAEz2JlMdvJADwZy', 'client');

-- Terrains
INSERT INTO terrains (nom, ville, prix, surface, description, image, statut) VALUES
('Complexe Sportif Oasis', 'Casablanca', 350.00, 1200, 'Grand terrain de football en gazon synthétique de dernière génération. Vestiaires et éclairage inclus.', 'oasis.jpg', 'disponible'),
('Arena Foot Rabat', 'Rabat', 400.00, 1000, 'Terrain couvert idéal pour le futsal, situé au centre-ville de Rabat. Cafétéria disponible.', 'arena_rabat.webp', 'disponible'),
('Stadium Marrakech', 'Marrakech', 500.00, 1500, 'Grand terrain multisport en plein air. Parfait pour football, rugby ou grands événements sportifs.', 'stadium_marrakech.jpg', 'disponible'),
('Club Omnisport Tanger', 'Tanger', 300.00, 800, 'Terrain de football à 7, proche de la corniche. Idéal pour des matchs entre amis le soir.', 'tanger_foot.webp', 'disponible');

-- Reservations
INSERT INTO reservations (user_id, terrain_id, date_debut, date_fin, montant_total, statut, created_at) VALUES
(2, 1, '2026-06-15', '2026-06-15', 700.00, 'acceptee', '2026-06-01 10:00:00'),
(2, 2, '2026-06-18', '2026-06-18', 400.00, 'en_attente', '2026-06-02 11:30:00'),
(3, 3, '2026-05-10', '2026-05-10', 1000.00, 'terminee', '2026-05-01 09:15:00'),
(3, 1, '2026-06-20', '2026-06-20', 350.00, 'en_attente', '2026-06-10 14:00:00');

-- Paiements
INSERT INTO paiements (reservation_id, montant, mode_paiement, date_paiement, statut, created_at) VALUES
(1, 700.00, 'carte', '2026-06-01', 'paye', '2026-06-01 10:05:00'),
(3, 1000.00, 'virement', '2026-05-02', 'paye', '2026-05-02 15:30:00');

SET FOREIGN_KEY_CHECKS = 1;
