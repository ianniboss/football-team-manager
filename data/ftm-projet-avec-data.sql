-- --------------------------------------------------------
-- Football Team Manager - Version compatible InfinityFree
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Drop tables in correct order (foreign keys first)
DROP TABLE IF EXISTS `participer`;
DROP TABLE IF EXISTS `commentaire`;
DROP TABLE IF EXISTS `rencontre`;
DROP TABLE IF EXISTS `joueur`;

-- Table: joueur
CREATE TABLE IF NOT EXISTS `joueur` (
  `id_joueur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `num_licence` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `taille` int DEFAULT NULL,
  `poids` decimal(5,2) DEFAULT NULL,
  `statut` enum('Actif','Blessé','Suspendu','Absent') NOT NULL DEFAULT 'Actif',
  PRIMARY KEY (`id_joueur`),
  UNIQUE KEY `num_licence` (`num_licence`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data: joueur
INSERT INTO `joueur` (`id_joueur`, `nom`, `prenom`, `num_licence`, `date_naissance`, `taille`, `poids`, `statut`) VALUES
	(1, 'Mbappé', 'Kylian', 'FR001', '1998-12-20', 178, 73.50, 'Actif'),
	(2, 'Griezmann', 'Antoine', 'FR002', '1991-03-21', 176, 70.00, 'Actif'),
	(3, 'Ramos', 'Sergio', 'ES001', '1986-03-30', 184, 82.00, 'Actif'),
	(4, 'Messi', 'Lionel', 'AR001', '1987-06-24', 170, 72.00, 'Blessé'),
	(5, 'Modric', 'Luka', 'HR001', '1985-09-09', 172, 66.00, 'Actif'),
	(6, 'Donnarumma', 'Gianluigi', 'IT001', '1999-02-25', 196, 90.00, 'Actif'),
	(7, 'Haaland', 'Erling', 'NO001', '2000-07-21', 194, 88.00, 'Actif'),
	(8, 'Ronaldo', 'Cristiano', 'PT001', '1985-02-05', 187, 83.00, 'Actif');

-- Table: rencontre
CREATE TABLE IF NOT EXISTS `rencontre` (
  `id_rencontre` int NOT NULL AUTO_INCREMENT,
  `date_rencontre` date NOT NULL,
  `heure` time NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `nom_equipe_adverse` varchar(50) NOT NULL,
  `lieu` enum('Domicile','Exterieur') NOT NULL,
  `resultat` enum('Victoire','Defaite','Nul') DEFAULT NULL,
  PRIMARY KEY (`id_rencontre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data: rencontre
INSERT INTO `rencontre` (`id_rencontre`, `date_rencontre`, `heure`, `adresse`, `nom_equipe_adverse`, `lieu`, `resultat`) VALUES
	(1, '2023-12-10', '15:00:00', 'Stade Municipal, Toulouse', 'FC Paris', 'Domicile', 'Victoire'),
	(2, '2023-12-18', '20:45:00', 'Parc des Princes, Paris', 'Paris SG', 'Exterieur', 'Defaite'),
	(3, '2024-01-05', '14:00:00', 'Stade Municipal, Toulouse', 'Olympique Lyon', 'Domicile', 'Nul'),
	(4, '2025-12-19', '16:00:00', 'Stade Vélodrome, Marseille', 'Olympique Marseille', 'Exterieur', NULL),
	(5, '2025-12-26', '21:00:00', 'Stade Municipal, Toulouse', 'AS Monaco', 'Domicile', NULL);

-- Table: commentaire
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int NOT NULL AUTO_INCREMENT,
  `id_joueur` int NOT NULL,
  `commentaire` text NOT NULL,
  `date_commentaire` date NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `fk_commentaire_joueur` (`id_joueur`),
  CONSTRAINT `fk_commentaire_joueur` FOREIGN KEY (`id_joueur`) REFERENCES `joueur` (`id_joueur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: participer
CREATE TABLE IF NOT EXISTS `participer` (
  `id_participation` int NOT NULL AUTO_INCREMENT,
  `id_rencontre` int NOT NULL,
  `id_joueur` int NOT NULL,
  `poste` varchar(50) NOT NULL,
  `titulaire` tinyint(1) NOT NULL DEFAULT '0',
  `evaluation` tinyint DEFAULT NULL,
  PRIMARY KEY (`id_participation`),
  UNIQUE KEY `uq_participer` (`id_rencontre`,`id_joueur`),
  KEY `fk_participer_joueur` (`id_joueur`),
  CONSTRAINT `fk_participer_joueur` FOREIGN KEY (`id_joueur`) REFERENCES `joueur` (`id_joueur`),
  CONSTRAINT `fk_participer_rencontre` FOREIGN KEY (`id_rencontre`) REFERENCES `rencontre` (`id_rencontre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
