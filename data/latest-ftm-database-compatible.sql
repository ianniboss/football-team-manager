-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

-- DISABLE FOREIGN KEY CHECKS TO PREVENT ERROR #1451
SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table commentaire
DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int NOT NULL AUTO_INCREMENT,
  `id_joueur` int NOT NULL,
  `commentaire` text NOT NULL,
  `date_commentaire` date NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `fk_commentaire_joueur` (`id_joueur`),
  CONSTRAINT `fk_commentaire_joueur` FOREIGN KEY (`id_joueur`) REFERENCES `joueur` (`id_joueur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table commentaire: ~4 rows
INSERT INTO `commentaire` (`id_commentaire`, `id_joueur`, `commentaire`, `date_commentaire`) VALUES
	(2, 7, 'dasdasdas', '2025-12-20'),
	(3, 7, 'dasdasdasdas', '2025-12-20'),
	(4, 2, 'good boy', '2025-12-20'),
	(5, 6, 'not good boy !', '2025-12-20');

-- Dumping structure for table joueur
DROP TABLE IF EXISTS `joueur`;
CREATE TABLE IF NOT EXISTS `joueur` (
  `id_joueur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `num_licence` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `taille` int DEFAULT NULL,
  `poids` decimal(5,2) DEFAULT NULL,
  `statut` enum('Actif','Blessé','Suspendu','Absent') NOT NULL DEFAULT 'Actif',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_joueur`),
  UNIQUE KEY `num_licence` (`num_licence`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table joueur: ~12 rows
INSERT INTO `joueur` (`id_joueur`, `nom`, `prenom`, `num_licence`, `date_naissance`, `taille`, `poids`, `statut`, `image`) VALUES
	(1, 'Mbappé', 'Kylian', 'FR001', '1998-12-20', 178, 73.50, 'Actif', 'kylian_mbappe_1.jpg'),
	(2, 'Griezmann', 'Antoine', 'FR002', '1991-03-21', 176, 70.00, 'Actif', 'antoine_griezmann_2.jpg'),
	(3, 'Ramos', 'Sergio', 'ES001', '1986-03-30', 184, 82.00, 'Actif', 'sergio_ramos_3.jpg'),
	(4, 'Messi', 'Lionel', 'AR001', '1987-06-24', 170, 72.00, 'Actif', 'lionel_messi_4.jpg'),
	(5, 'Modric', 'Luka', 'HR001', '1985-09-09', 172, 66.00, 'Actif', 'luka_modric_5.jpg'),
	(6, 'Donnarumma', 'Gianluigi', 'IT001', '1999-02-25', 196, 90.00, 'Actif', 'gianluigi_donnarumma_6.jpg'),
	(7, 'Haaland', 'Erling', 'NO001', '2000-07-21', 194, 88.00, 'Actif', 'erling_haaland_7.jpg'),
	(8, 'Ronaldo', 'Cristiano', 'PT001', '1985-02-05', 187, 83.00, 'Actif', 'cristiano_ronaldo_8.jpg'),
	(9, 'Zidane', 'Zinedine', 'AB2024', '1972-06-23', 185, 80.00, 'Actif', 'zinedine_zidane_9.jpg'),
	(10, 'Van Dijk', 'Virgil', 'CT045', '1991-07-08', 193, 92.00, 'Actif', 'virgil_van_dijk_10.jpg'),
	(11, 'Maignan', 'Mike', 'UI0909', '1995-07-03', 191, 89.00, 'Actif', 'mike_maignan_11.webp'),
	(12, 'Salah', 'Mohamed', 'QW1234', '1992-06-15', 175, 71.00, 'Actif', 'mohamed_salah_12.jpg');

-- Dumping structure for table participer
DROP TABLE IF EXISTS `participer`;
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
  CONSTRAINT `fk_participer_rencontre` FOREIGN KEY (`id_rencontre`) REFERENCES `rencontre` (`id_rencontre`),
  CONSTRAINT `chk_evaluation` CHECK (((`evaluation` between 1 and 5) or (`evaluation` is null)))
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table participer: ~23 rows
INSERT INTO `participer` (`id_participation`, `id_rencontre`, `id_joueur`, `poste`, `titulaire`, `evaluation`) VALUES
	(2, 6, 2, 'Attaquant', 1, 3),
	(3, 6, 7, 'Attaquant', 1, NULL),
	(4, 6, 11, 'Défenseur Central', 1, NULL),
	(5, 6, 1, 'Attaquant', 1, NULL),
	(6, 6, 4, 'Ailier Gauche', 1, NULL),
	(7, 6, 5, 'Milieu Offensif', 1, NULL),
	(8, 6, 3, 'Gardien', 1, NULL),
	(9, 6, 8, 'Défenseur Central', 1, NULL),
	(10, 6, 12, 'Défenseur Latéral Droit', 1, NULL),
	(11, 6, 10, 'Milieu Offensif', 1, NULL),
	(12, 6, 9, 'Milieu Central', 1, NULL),
	(13, 6, 6, '', 0, NULL),
	(14, 3, 6, '', 1, 5),
	(15, 3, 2, '', 1, 5),
	(16, 3, 7, '', 1, 5),
	(17, 3, 11, '', 1, 5),
	(18, 3, 1, '', 1, 5),
	(19, 3, 4, '', 1, 5),
	(20, 3, 5, '', 1, 5),
	(21, 3, 3, '', 1, 5),
	(22, 3, 8, '', 1, 5),
	(23, 3, 10, '', 1, 5),
	(24, 3, 9, '', 1, 5);

-- Dumping structure for table rencontre
DROP TABLE IF EXISTS `rencontre`;
CREATE TABLE IF NOT EXISTS `rencontre` (
  `id_rencontre` int NOT NULL AUTO_INCREMENT,
  `date_rencontre` date NOT NULL,
  `heure` time NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `nom_equipe_adverse` varchar(50) NOT NULL,
  `lieu` enum('Domicile','Exterieur') NOT NULL,
  `resultat` enum('Victoire','Defaite','Nul') DEFAULT NULL,
  `image_stade` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_rencontre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table rencontre: ~6 rows
INSERT INTO `rencontre` (`id_rencontre`, `date_rencontre`, `heure`, `adresse`, `nom_equipe_adverse`, `lieu`, `resultat`, `image_stade`) VALUES
	(1, '2023-12-10', '15:00:00', 'Stade Municipal, Toulouse', 'FC Paris', 'Domicile', 'Victoire', 'stade_municipal.avif'),
	(2, '2023-12-18', '20:45:00', 'Parc des Princes, Paris', 'Paris SG', 'Exterieur', 'Defaite', 'parc_des_princes.jpeg'),
	(3, '2024-01-05', '14:00:00', 'Stade Municipal, Toulouse', 'Olympique Lyon', 'Domicile', 'Victoire', 'stade_municipal.avif'),
	(4, '2025-12-19', '16:00:00', 'Stade Vélodrome, Marseille', 'Olympique Marseille', 'Exterieur', NULL, 'stade_velodrome.png'),
	(5, '2025-12-26', '21:00:00', 'Stade Municipal, Toulouse', 'AS Monaco', 'Domicile', 'Defaite', 'stade_municipal.avif'),
	(6, '2026-01-01', '10:00:00', 'Camp Nou', 'Barcelona FC', 'Domicile', NULL, 'camp_nou.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

-- RE-ENABLE FOREIGN KEY CHECKS
SET FOREIGN_KEY_CHECKS = 1;
