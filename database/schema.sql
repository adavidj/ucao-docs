-- Création des tables pour la base de données ucao_docs_archive

CREATE TABLE IF NOT EXISTS `ecoles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(255) NOT NULL,
  `type` ENUM('ecole', 'faculte') NOT NULL
);

CREATE TABLE IF NOT EXISTS `filieres` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(255) NOT NULL,
  `ecole_id` INT NOT NULL,
  FOREIGN KEY (`ecole_id`) REFERENCES `ecoles`(`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `niveaux` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(255) NOT NULL -- e.g., Licence 1, Licence 2, Master 1
);

CREATE TABLE IF NOT EXISTS `documents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titre` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `type` ENUM('epreuve', 'cours', 'autre') NOT NULL,
  `session` VARCHAR(50), -- e.g., Session normale, Rattrapage
  `annee` YEAR,
  `fichier` VARCHAR(255) NOT NULL, -- Chemin vers le fichier
  `filiere_id` INT NOT NULL,
  `niveau_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`filiere_id`) REFERENCES `filieres`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`niveau_id`) REFERENCES `niveaux`(`id`) ON DELETE CASCADE
);

-- Données initiales
INSERT INTO `ecoles` (`nom`, `type`) VALUES
('École de Génie Électrique et Informatique (EGEI)', 'ecole'),
('École de Management et Économie Appliquée (ESMEA)', 'ecole'),
('Faculté des Sciences de l''Agronomie et de l''Environnement (FSAE)', 'faculte'),
('Faculté de Droit et d''Économie (FDE)', 'faculte');

INSERT INTO `filieres` (`nom`, `ecole_id`) VALUES
-- EGEI
('Électronique', 1),
('Génie Télécoms et TIC', 1),
('Informatique Industrielle et Maintenance', 1),
('Système Industriel- Electrotechnique', 1),
-- ESMEA
('Banques Finances et Assurances', 2),
('Finances Comptabilité Audit', 2),
('Management des Ressources Humaines', 2),
('Markéting Communication et Commerce', 2),
('Système Informatique et Logiciel', 2),
('Transport et Logistique', 2),
-- FSAE
('Gestion de l''Environnement et Aménagement du Territoire', 3),
('Production et Gestion des Ressources Animales', 3),
('Sciences et Techniques de Production Végétale', 3),
('Stockage Conservation et Conditionnement des Produits Agricoles', 3),
('Gestion des Entreprises Rurales et Agricoles', 3),
-- FDE
('Sciences Juridiques', 4),
('Droit', 4),
('Sciences Économiques', 4),
('Économie', 4);

INSERT INTO `niveaux` (`nom`) VALUES
('Licence 1'),
('Licence 2'),
('Licence 3'),
('Master 1'),
('Master 2');
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
